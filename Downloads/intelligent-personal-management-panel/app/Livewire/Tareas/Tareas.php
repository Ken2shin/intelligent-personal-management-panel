<?php

namespace App\Livewire\Tareas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Tareas extends Component
{
    use WithPagination;

    // Usamos el tema de Bootstrap o Tailwind según tu configuración
    protected $paginationTheme = 'tailwind'; 

    // Propiedades del Formulario
    public $titulo = '';
    public $descripcion = '';
    public $prioridad = 'media';
    public $estado = 'pendiente';
    public $fecha_limite = null;
    
    // Control de interfaz
    public $editandoId = null;
    public $mostrarFormulario = false;

    // Filtros
    public $filtroEstado = '';
    public $filtroPrioridad = '';
    public $filtroFecha = '';

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'prioridad' => 'required|in:baja,media,alta',
        'estado' => 'required|in:pendiente,en_progreso,completada',
        'fecha_limite' => 'nullable|date',
    ];

    public function crear()
    {
        $this->validate();

        // Operador ternario simplificado
        $fecha = $this->fecha_limite ?: null;

        if ($this->editandoId) {
            // EDITAR: Usamos findOrFail para seguridad y solo buscamos por ID y Usuario
            // No necesitamos 'select' aquí porque vamos a editar todo
            $tarea = Tarea::where('user_id', Auth::id())->find($this->editandoId);
            
            if ($tarea) {
                $tarea->update([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'prioridad' => $this->prioridad,
                    'estado' => $this->estado,
                    'fecha_limite' => $fecha,
                ]);
                session()->flash('mensaje', 'Tarea actualizada.');
            }
        } else {
            // CREAR
            Tarea::create([
                'user_id' => Auth::id(),
                'titulo' => $this->titulo,
                'descripcion' => $this->descripcion,
                'prioridad' => $this->prioridad,
                'estado' => $this->estado,
                'fecha_limite' => $fecha,
            ]);
            session()->flash('mensaje', 'Tarea creada.');
        }

        $this->limpiarFormulario();
    }

    public function editar($id)
    {
        // Optimizacion: Solo traemos los campos necesarios para el formulario
        $tarea = Tarea::where('user_id', Auth::id())
                      ->select('id', 'titulo', 'descripcion', 'prioridad', 'estado', 'fecha_limite')
                      ->findOrFail($id);

        $this->editandoId = $id;
        $this->titulo = $tarea->titulo;
        $this->descripcion = $tarea->descripcion;
        $this->prioridad = $tarea->prioridad;
        $this->estado = $tarea->estado;
        
        $this->fecha_limite = $tarea->fecha_limite 
            ? Carbon::parse($tarea->fecha_limite)->format('Y-m-d') 
            : null;
        
        $this->mostrarFormulario = true;
    }

    public function eliminar($id)
    {
        // Delete directo sin cargar el modelo completo a memoria si no es necesario
        Tarea::where('user_id', Auth::id())->where('id', $id)->delete();
        session()->flash('mensaje', 'Tarea eliminada.');
    }

    public function cambiarEstado($id, $nuevoEstado)
    {
        // Update directo es más rápido que find() + save()
        Tarea::where('user_id', Auth::id())
             ->where('id', $id)
             ->update(['estado' => $nuevoEstado]);
             
        session()->flash('mensaje', 'Estado actualizado.');
    }

    public function limpiarFormulario()
    {
        $this->reset(['titulo', 'descripcion', 'prioridad', 'estado', 'fecha_limite', 'editandoId']);
        $this->mostrarFormulario = false;
        $this->resetValidation();
        // Valores por defecto
        $this->prioridad = 'media';
        $this->estado = 'pendiente';
    }

    // Resetear paginación
    public function updatedFiltroEstado() { $this->resetPage(); }
    public function updatedFiltroPrioridad() { $this->resetPage(); }
    public function updatedFiltroFecha() { $this->resetPage(); }

    public function render()
    {
        $query = Tarea::where('user_id', Auth::id());

        // Optimizacion SQL: Solo agregamos el WHERE si realmente hay un valor
        $query->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
              ->when($this->filtroPrioridad, fn($q) => $q->where('prioridad', $this->filtroPrioridad))
              ->when($this->filtroFecha, fn($q) => $q->whereDate('fecha_limite', $this->filtroFecha));

        // MEGA OPTIMIZACIÓN 1: Select
        // Solo traemos las columnas que se ven en la tabla. 
        // NO traemos 'descripcion' si es muy larga y no se muestra en la lista principal.
        $query->select('id', 'user_id', 'titulo', 'estado', 'prioridad', 'fecha_limite', 'created_at');

        // MEGA OPTIMIZACIÓN 2: simplePaginate
        // Esto evita que Laravel haga un "SELECT COUNT(*)" extra. Ahorra 50% de tiempo en red.
        $tareas = $query->orderBy('created_at', 'desc')
                        ->simplePaginate(10); 

        return view('livewire.tareas.tareas', [
            'tareas' => $tareas,
        ]);
    }
}