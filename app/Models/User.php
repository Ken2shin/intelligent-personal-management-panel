<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function habitos()
    {
        return $this->hasMany(Habito::class);
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    // Métodos optimizados con caching
    public function tareasCompletadas()
    {
        return cache()->remember(
            "user.{$this->id}.tareas_completadas",
            3600,
            fn () => $this->tareas()->where('estado', 3)->count()
        );
    }

    public function tareasHoy()
    {
        return cache()->remember(
            "user.{$this->id}.tareas_hoy",
            300,
            fn () => $this->tareas()
                ->whereDate('fecha_limite', today())
                ->where('estado', '!=', 3)
                ->count()
        );
    }

    public function balanceTotal()
    {
        return cache()->remember(
            "user.{$this->id}.balance",
            3600,
            function () {
                $ingresos = $this->transacciones()
                    ->where('tipo', 1)
                    ->sum('monto');
                
                $gastos = $this->transacciones()
                    ->where('tipo', 0)
                    ->sum('monto');

                return $ingresos - $gastos;
            }
        );
    }

    public function habitosCompletadosHoy()
    {
        return cache()->remember(
            "user.{$this->id}.habitos_hoy",
            300,
            fn () => $this->habitos()
                ->with(['registros' => function ($query) {
                    $query->whereDate('fecha', today())->where('completado', true);
                }])
                ->count()
        );
    }

    // Clear related caches
    public function clearCaches()
    {
        cache()->forget("user.{$this->id}.tareas_completadas");
        cache()->forget("user.{$this->id}.tareas_hoy");
        cache()->forget("user.{$this->id}.balance");
        cache()->forget("user.{$this->id}.habitos_hoy");
    }
}
