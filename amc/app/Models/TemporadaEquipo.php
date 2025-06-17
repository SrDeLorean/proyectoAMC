<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemporadaEquipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_temporadacompetencia',
        'id_equipo',
        'puntos',
        'partidos_jugados',
        'victorias',
        'empates',
        'derrotas',
        'goles_a_favor',
        'goles_en_contra',
        'diferencia_goles',
    ];

    public function temporadaCompetencia()
    {
        return $this->belongsTo(TemporadaCompetencia::class, 'id_temporadacompetencia');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }
}
