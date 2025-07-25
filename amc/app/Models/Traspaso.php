<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Traspaso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_jugador',
        'id_equipo_origen',
        'id_equipo_destino',
        'motivo',
        'estado',
        'fecha_traspaso',
        'fecha_respuesta_jugador',
        'fecha_revision_admin',
    ];

    // Relaciones
    public function jugador()
    {
        return $this->belongsTo(User::class, 'id_jugador');
    }

    public function equipoOrigen()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo_origen');
    }

    public function equipoDestino()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo_destino');
    }
}
