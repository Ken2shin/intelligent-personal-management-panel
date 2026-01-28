<?php

namespace App\Livewire\Finanzas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // Importante para la optimización
use Illuminate\Support\Facades\DB;

class Finanzas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $tipo = 'gasto';
    public $categoria = '';
    public $monto = '';
    public $fecha = null;
    public $descripcion = '';
    
    public $editandoId = null;
    public $mostrarFormulario = false;
    
    // Filtros
    public $filtroTipo = null;
    public $filtroCategoria = null;
    public $fechaInicio = null;
    public $fechaFin = null;

    // Resetear paginación al filtrar
    public function updatedFiltroTipo() { $this->resetPage(); }
    public function updatedFiltroCategoria() { $this->resetPage(); }
    public function updatedFechaInicio() { $this->resetPage(); }
    public function updatedFechaFin() { $this->resetPage(); }

    protected $rules = [
        'tipo' => 'required|in:ingreso,gasto',
        'categoria' => 'required|string|max:255',
        'monto' => 'required|numeric|min:0.01',
        'fecha' => 'required|date',
        'descripcion' => 'nullable|string',
    ];

    public function mount()
    {
        // Inicializamos la fecha solo si está vacía
        $this->fecha = date('Y-m-d');
    }

    public function crear()
    {
        $this->validate();

        // Limpiamos caché de categorías por si el usuario creó una nueva
        Cache::forget('categorias_user_' . Auth::id());

        $datos = [
            'tipo' => $this->tipo,
            'categoria' => $this->categoria,
            'monto' => $this->monto,
            'fecha' => $this->fecha,
            'descripcion' => $this->descripcion,
        ];

        if ($this->editandoId) {
            // Update directo
            Transaccion::where('id', $this->editandoId)
                ->where('user_id', Auth::id())
                ->update($datos);
                
            $this->dispatch('notify', type: 'success', message: 'Transacción actualizada');
            $this->editandoId = null;
        } else {
            // Create directo
            Transaccion::create(array_merge($datos, ['user_id' => Auth::id()]));
            $this->dispatch('notify', type: 'success', message: 'Transacción creada');
        }

        $this->limpiarFormulario();
    }

    public function editar($id)
    {
        $transaccion = Transaccion::where('user_id', Auth::id())
            ->select('id', 'tipo', 'categoria', 'monto', 'fecha', 'descripcion')
            ->findOrFail($id);
            
        $this->editandoId = $id;
        $this->tipo = $transaccion->tipo;
        $this->categoria = $transaccion->categoria;
        $this->monto = $transaccion->monto;
        $this->fecha = $transaccion->fecha->format('Y-m-d');
        $this->descripcion = $transaccion->descripcion;
        $this->mostrarFormulario = true;
    }

    public function eliminar($id)
    {
        Transaccion::where('id', $id)->where('user_id', Auth::id())->delete();
        // Limpiamos caché por si se borró la última transacción de una categoría
        Cache::forget('categorias_user_' . Auth::id());
        
        $this->dispatch('notify', type: 'success', message: 'Transacción eliminada');
    }

    public function limpiarFormulario()
    {
        $this->reset(['tipo', 'categoria', 'monto', 'descripcion', 'editandoId', 'mostrarFormulario']);
        $this->tipo = 'gasto';
        $this->fecha = date('Y-m-d'); // Mantener fecha hoy
        $this->resetValidation();
    }

    public function render()
    {
        $userId = Auth::id();
        
        // 1. Consulta Principal (Filtros)
        $query = Transaccion::where('user_id', $userId);

        if ($this->filtroTipo) $query->where('tipo', $this->filtroTipo);
        if ($this->filtroCategoria) $query->where('categoria', $this->filtroCategoria);
        if ($this->fechaInicio) $query->whereDate('fecha', '>=', $this->fechaInicio);
        if ($this->fechaFin) $query->whereDate('fecha', '<=', $this->fechaFin);

        // OPTIMIZACIÓN 1: simplePaginate y Select
        $transacciones = $query->orderBy('fecha', 'desc')
            ->select('id', 'categoria', 'tipo', 'monto', 'fecha', 'descripcion')
            ->simplePaginate(15); // Mucho más rápido que paginate()

        // OPTIMIZACIÓN 2: Cálculo de Totales en UNA SOLA consulta
        // En lugar de hacer 2 consultas separadas sum(), hacemos 1 sola agregada.
        // Ahorra 1 ida y vuelta al servidor (aprox 200ms de ganancia).
        $balance = Transaccion::where('user_id', $userId)
            ->selectRaw("SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as total_ingresos")
            ->selectRaw("SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END) as total_gastos")
            ->first();

        // OPTIMIZACIÓN 3: Caché de Categorías
        // Guardamos las categorías en caché por 1 hora. 
        // Solo se consulta a la DB si no existe en caché.
        $categorias = Cache::remember('categorias_user_' . $userId, 3600, function () use ($userId) {
            return Transaccion::where('user_id', $userId)
                ->select('categoria')
                ->distinct()
                ->pluck('categoria');
        });

        return view('livewire.finanzas.finanzas', [
            'transacciones' => $transacciones,
            'ingresos' => $balance->total_ingresos ?? 0,
            'gastos' => $balance->total_gastos ?? 0,
            'categorias' => $categorias,
        ]);
    }
}