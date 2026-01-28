<?php

namespace App\Livewire\Habitos;

use Livewire\Component;
use App\Models\Habito;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Habitos extends Component
{
    public $nombre = '';
    public $descripcion = '';
    public $editandoId = null;
    public $mostrarFormulario = false;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    public function crear()
    {
        $this->validate();

        if ($this->editandoId) {
            // OPTIMIZACIÓN 1: Update Directo
            // Actualiza en SQL directamente sin cargar el objeto PHP.
            Habito::where('id', $this->editandoId)
                ->where('user_id', Auth::id())
                ->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                ]);
                
            $this->dispatch('notify', type: 'success', message: 'Hábito actualizado');
            $this->editandoId = null;
        } else {
            Habito::create([
                'user_id' => Auth::id(),
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'racha' => 0, // Aseguramos valor inicial
                'activo' => true
            ]);
            $this->dispatch('notify', type: 'success', message: 'Hábito creado');
        }

        $this->limpiarFormulario();
    }

    public function editar($id)
    {
        // Solo traemos campos necesarios
        $habito = Habito::where('user_id', Auth::id())
                        ->select('id', 'nombre', 'descripcion')
                        ->findOrFail($id);
                        
        $this->editandoId = $id;
        $this->nombre = $habito->nombre;
        $this->descripcion = $habito->descripcion;
        $this->mostrarFormulario = true;
    }

    public function eliminar($id)
    {
        // OPTIMIZACIÓN 2: Delete Directo
        Habito::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', type: 'success', message: 'Hábito eliminado');
    }

    public function marcarCompletado($id)
    {
        // Buscamos el modelo porque tu lógica de negocio (marcarCompletado) 
        // probablemente está dentro del modelo Habito.php
        $habito = Habito::where('user_id', Auth::id())->select('id')->find($id);
        
        if ($habito) {
            $habito->marcarCompletado(); // Esto dispara el trigger de PostgreSQL que actualiza la racha
            $this->dispatch('notify', type: 'success', message: '¡Hábito completado!');
        }
    }

    public function desmarcarCompletado($id)
    {
        $habito = Habito::where('user_id', Auth::id())->select('id')->find($id);
        
        if ($habito) {
            $habito->desmarcarCompletado();
            $this->dispatch('notify', type: 'info', message: 'Hábito desmarcado');
        }
    }

    public function limpiarFormulario()
    {
        $this->reset(['nombre', 'descripcion', 'editandoId', 'mostrarFormulario']);
        $this->resetValidation();
    }

    public function render()
    {
        // MEGA OPTIMIZACIÓN 3: Carga Inteligente
        // En lugar de traer 'registros' (que pueden ser miles), 
        // usamos 'withCount' condicional para saber SI HOY se completó.
        // Esto devuelve un campo extra 'completado_hoy' con valor 1 o 0.
        
        $habitos = Habito::where('user_id', Auth::id())
            ->select('id', 'nombre', 'descripcion', 'racha', 'activo', 'created_at') // Solo lo que se ve
            ->withCount(['registros as completado_hoy' => function ($query) {
                $query->whereDate('fecha', Carbon::today());
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.habitos.habitos', [
            'habitos' => $habitos,
        ]);
    }
}