<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemporadaCompetencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'id_temporada',
        'id_competencia',
        'formato', // 'liga' o 'copa'
        'fecha_inicio',
        'fecha_termino',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_termino' => 'datetime',
    ];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada');
    }

    public function equipos()
    {
        return $this->hasMany(TemporadaEquipo::class, 'id_temporadacompetencia');
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'id_competencia');
    }

    public function calendarios()
    {
        return $this->hasMany(Calendario::class, 'id_temporadacompetencia');
    }

    public function temporadaEquipos()
    {
        return $this->hasMany(TemporadaEquipo::class, 'id_temporadacompetencia');
    }


    public function obtenerTablaClasificacion()
    {
        return $this->temporadaEquipos()
            ->with('equipo')
            ->get()
            ->map(function($temporadaEquipo) {
                return [
                    'id' => $temporadaEquipo->equipo->id,
                    'nombre' => $temporadaEquipo->equipo->nombre,
                    'puntos' => $temporadaEquipo->puntos,
                    'partidos_jugados' => $temporadaEquipo->partidos_jugados,
                    'victorias' => $temporadaEquipo->victorias,
                    'empates' => $temporadaEquipo->empates,
                    'derrotas' => $temporadaEquipo->derrotas,
                    'goles_a_favor' => $temporadaEquipo->goles_a_favor,
                    'goles_en_contra' => $temporadaEquipo->goles_en_contra,
                    'diferencia_goles' => $temporadaEquipo->goles_a_favor - $temporadaEquipo->goles_en_contra,
                ];
            })
            ->sortByDesc('puntos')
            ->values()
            ->toArray();
    }


}
