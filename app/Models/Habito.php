<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habito extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'racha',
        'puntos',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registros()
    {
        return $this->hasMany(HabitoRegistro::class);
    }

    public function marcarCompletado()
    {
        $hoy = today();
        $registro = HabitoRegistro::firstOrCreate(
            [
                'habito_id' => $this->id,
                'fecha' => $hoy,
            ],
            ['completado' => true]
        );

        if ($registro->completado) {
            return;
        }

        $registro->update(['completado' => true]);
        $this->increment('racha');
        $this->increment('puntos', 10);
    }

    public function desmarcarCompletado()
    {
        $hoy = today();
        HabitoRegistro::where('habito_id', $this->id)
            ->whereDate('fecha', $hoy)
            ->update(['completado' => false]);

        if ($this->racha > 0) {
            $this->decrement('racha');
        }
        $this->decrement('puntos', 10);
    }

    public function registroHoy()
    {
        return $this->registros()
            ->whereDate('fecha', today())
            ->first();
    }

    public function completadoHoy()
    {
        $registro = $this->registroHoy();
        return $registro && $registro->completado;
    }
}
