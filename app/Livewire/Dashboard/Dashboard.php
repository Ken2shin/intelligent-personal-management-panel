<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Lazy; // Vital para velocidad percibida
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Usaremos DB directo para velocidad
use Carbon\Carbon;
use App\Models\Tarea;       // Solo para listas
use App\Models\Transaccion; // Solo para listas

#[Lazy]
class Dashboard extends Component
{
    // Propiedades de contadores
    public $tareasCompletadas = 0;
    public $tareasHoy = 0;
    public $habitosHoy = 0;
    public $notasCreadas = 0;
    public $balanceTotal = 0;

    // Listas (las cargamos en render para no bloquear el mount)
    
    // Placeholder: Lo que se ve mientras cargan los datos (esqueleto)
    public function placeholder()
    {
        return view('livewire.dashboard.placeholder');
    }

    public function mount()
    {
        $this->cargarContadoresOptimizado();
    }

    public function cargarContadoresOptimizado()
    {
        $userId = Auth::id();
        $hoy = Carbon::today()->toDateString(); // Formato 'Y-m-d' para SQL

        // --- OPTIMIZACIÓN 1: Finanzas en 1 sola consulta ---
        // Usamos selectRaw para sumar ingresos y gastos en el mismo viaje
        $finanzas = DB::table('transacciones')
            ->where('user_id', $userId)
            ->selectRaw("SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as ingresos")
            ->selectRaw("SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END) as gastos")
            ->first();

        $this->balanceTotal = ($finanzas->ingresos ?? 0) - ($finanzas->gastos ?? 0);

        // --- OPTIMIZACIÓN 2: Tareas en 1 sola consulta ---
        // Usamos FILTER de PostgreSQL que es ultra rápido para contar condicionalmente
        $statsTareas = DB::table('tareas')
            ->where('user_id', $userId)
            ->selectRaw("COUNT(*) FILTER (WHERE estado = 'completada') as completadas")
            ->selectRaw("COUNT(*) FILTER (WHERE estado != 'completada' AND fecha_limite::date <= ?) as pendientes_hoy", [$hoy])
            ->first();

        $this->tareasCompletadas = $statsTareas->completadas ?? 0;
        $this->tareasHoy = $statsTareas->pendientes_hoy ?? 0;

        // --- OPTIMIZACIÓN 3: Notas (Count directo) ---
        $this->notasCreadas = DB::table('notas')->where('user_id', $userId)->count();

        // --- OPTIMIZACIÓN 4: Hábitos (Join ligero) ---
        // Hacemos JOIN porque es más rápido que whereHas para contar
        $this->habitosHoy = DB::table('habito_registros')
            ->join('habitos', 'habito_registros.habito_id', '=', 'habitos.id')
            ->where('habitos.user_id', $userId)
            ->where('habito_registros.fecha', $hoy)
            ->where('habito_registros.completado', true)
            ->count();
    }

    public function render()
    {
        $userId = Auth::id();
        $hoy = Carbon::now();

        // Lista de Tareas: Optimizada con Select y Orden Real por Prioridad
        $tareasDelDia = Tarea::where('user_id', $userId)
            ->select('id', 'titulo', 'estado', 'prioridad', 'fecha_limite') // Solo lo necesario
            ->where('estado', '!=', 'completada')
            ->whereDate('fecha_limite', '<=', $hoy)
            ->orderByRaw("CASE 
                WHEN prioridad = 'alta' THEN 1 
                WHEN prioridad = 'media' THEN 2 
                ELSE 3 END") // Orden lógico: Alta > Media > Baja
            ->orderBy('fecha_limite', 'asc')
            ->limit(5)
            ->get();

        // Lista de Transacciones: Optimizada con Select
        $transaccionesRecientes = Transaccion::where('user_id', $userId)
            ->select('id', 'categoria', 'descripcion', 'tipo', 'monto', 'created_at')
            ->orderByDesc('fecha')
            ->orderByDesc('created_at') // Desempate para mostrar las recién creadas primero
            ->limit(5)
            ->get();

        return view('livewire.dashboard.dashboard', [
            'tareasDelDia' => $tareasDelDia,
            'transaccionesRecientes' => $transaccionesRecientes,
        ]);
    }
}