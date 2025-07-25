<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemporadaPlantilla extends Model
{
    use SoftDeletes;

    protected $table = 'temporada_plantillas';

    protected $fillable = [
        'id_temporada_equipo',
        'id_jugador',
        'rol',
        'posicion',
        'numero',
        'fecha_ingreso',
        'fecha_salida',
    ];

    public function jugador()
    {
        return $this->belongsTo(User::class, 'id_jugador');
    }

    public function temporadaEquipo()
    {
        return $this->belongsTo(TemporadaEquipo::class, 'id_temporada_equipo');
    }

    public function equipo()
    {
        return $this->hasOneThrough(
            Equipo::class,
            TemporadaEquipo::class,
            'id', // Foreign key on TemporadaEquipo table...
            'id', // Foreign key on Equipo table...
            'id_temporada_equipo', // Local key on TemporadaPlantilla table...
            'id_equipo' // Local key on TemporadaEquipo table...
        );
    }
}
