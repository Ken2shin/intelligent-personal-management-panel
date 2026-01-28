<?php

namespace App\Livewire\Notas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Nota;
use Illuminate\Support\Facades\Auth;

class Notas extends Component
{
    use WithPagination;

    // Tema de paginación compatible con Tailwind
    protected $paginationTheme = 'tailwind';

    // Propiedades
    public $titulo = '';
    public $contenido = '';
    public $tags = '';
    public $editandoId = null;
    public $mostrarFormulario = false;
    
    // Filtros
    public $busqueda = '';
    public $mostrarArchivadas = false;

    // OPTIMIZACIÓN 1: Resetear página al buscar
    // Esto evita bugs visuales y recargas innecesarias cuando filtras
    public function updatedBusqueda() { $this->resetPage(); }
    public function updatedMostrarArchivadas() { $this->resetPage(); }

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'contenido' => 'nullable|string',
        'tags' => 'nullable|string',
    ];

    public function crear()
    {
        $this->validate();

        $datos = [
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'tags' => $this->tags,
        ];

        if ($this->editandoId) {
            // OPTIMIZACIÓN 2: Update Directo
            // Actualizamos directo en SQL sin cargar el modelo completo en memoria.
            // Ahorra una ida y vuelta a la base de datos.
            Nota::where('id', $this->editandoId)
                ->where('user_id', Auth::id())
                ->update($datos);
                
            $this->dispatch('notify', type: 'success', message: 'Nota actualizada');
        } else {
            // Crear
            Nota::create(array_merge($datos, ['user_id' => Auth::id()]));
            $this->dispatch('notify', type: 'success', message: 'Nota creada');
        }

        $this->limpiarFormulario();
    }

    public function editar($id)
    {
        // Solo traemos lo necesario para editar, asegurando que sea del usuario
        $nota = Nota::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $this->editandoId = $id;
        $this->titulo = $nota->titulo;
        $this->contenido = $nota->contenido;
        $this->tags = $nota->tags;
        $this->mostrarFormulario = true;
    }

    public function eliminar($id)
    {
        // OPTIMIZACIÓN 3: Delete Directo
        // Borramos directo por Query Builder. Mucho más rápido que find() + delete().
        Nota::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', type: 'success', message: 'Nota eliminada');
    }

    public function archivar($id)
    {
        // Update directo booleano
        Nota::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['archivada' => true]);
            
        $this->dispatch('notify', type: 'success', message: 'Nota archivada');
    }

    public function desarchivar($id)
    {
        // Update directo booleano
        Nota::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['archivada' => false]);
            
        $this->dispatch('notify', type: 'success', message: 'Nota desarchivada');
    }

    public function limpiarFormulario()
    {
        $this->reset(['titulo', 'contenido', 'tags', 'editandoId', 'mostrarFormulario']);
        $this->resetValidation();
    }

    public function render()
    {
        $userId = Auth::id();
        
        // Iniciamos la consulta solo para el usuario actual
        $query = Nota::where('user_id', $userId);

        if (!$this->mostrarArchivadas) {
            $query->where('archivada', false);
        }

        if ($this->busqueda) {
            // OPTIMIZACIÓN 4: PostgreSQL 'ilike'
            // 'ilike' es insensible a mayúsculas/minúsculas nativamente en Postgres.
            // Es mucho más eficiente que convertir todo a minúsculas en PHP.
            $term = '%' . $this->busqueda . '%';
            $query->where(function ($q) use ($term) {
                $q->where('titulo', 'ilike', $term)
                  ->orWhere('contenido', 'ilike', $term)
                  ->orWhere('tags', 'ilike', $term);
            });
        }

        // OPTIMIZACIÓN 5: Selección de Campos
        // Solo traemos las columnas que usa la tarjeta de vista previa.
        $query->select('id', 'user_id', 'titulo', 'contenido', 'tags', 'archivada', 'created_at');

        // OPTIMIZACIÓN 6: simplePaginate
        // Carga solo 12 notas a la vez (ideal para grids).
        // Evita contar el total de registros, lo que acelera la consulta un 50%.
        $notas = $query->orderBy('created_at', 'desc')
                       ->simplePaginate(12);

        return view('livewire.notas.notas', [
            'notas' => $notas,
        ]);
    }
}