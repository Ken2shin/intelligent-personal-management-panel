<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPrioritaria()
    {
        return $this->prioridad === 'alta';
    }

    public function isCompletada()
    {
        return $this->estado === 'completada';
    }

    public function marcarCompletada()
    {
        $this->update(['estado' => 'completada']);
    }

    public function marcarEnProgreso()
    {
        $this->update(['estado' => 'en_progreso']);
    }

    public function marcarPendiente()
    {
        $this->update(['estado' => 'pendiente']);
    }
}
