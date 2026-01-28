<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    /**
     * CORRECCIÓN: Definimos explícitamente el nombre de la tabla
     * para evitar que Laravel busque "transaccions".
     *
     * Si tu tabla en la base de datos se llama 'transactions' (en inglés),
     * cambia 'transacciones' por 'transactions' abajo.
     */
    protected $table = 'transacciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'categoria',
        'monto',
        'fecha',
        'descripcion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function esIngreso()
    {
        return $this->tipo === 'ingreso';
    }

    public function esGasto()
    {
        return $this->tipo === 'gasto';
    }
}