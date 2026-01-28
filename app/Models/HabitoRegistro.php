<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitoRegistro extends Model
{
    use HasFactory;

    protected $fillable = [
        'habito_id',
        'fecha',
        'completado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'completado' => 'boolean',
    ];

    public function habito()
    {
        return $this->belongsTo(Habito::class);
    }
}
