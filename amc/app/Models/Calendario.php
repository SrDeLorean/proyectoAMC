<?php

// app/Models/Calendario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendario extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'id_temporadacompetencia',
        'id_equipo_local',
        'id_equipo_visitante',
        'goles_equipo_local',
        'goles_equipo_visitante',
        'jornada',
        'fecha',
        'hora',
        'foto_rendimiento_local',
        'foto_lista_id_local',
        'foto_rendimiento_jugadores_local',
        'foto_rendimiento_visitante',
        'foto_lista_id_visitante',
        'foto_rendimiento_jugadores_visitante',
    ];

    public function temporadaCompetencia()
    {
        return $this->belongsTo(TemporadaCompetencia::class, 'id_temporadacompetencia');
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo_local');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo_visitante');
    }

    public function estadisticasJugadores()
    {
        return $this->hasMany(EstadisticaJugador::class, 'id_calendario');
    }

    public function estadisticasEquipos()
    {
        return $this->hasMany(EstadisticaEquipo::class, 'id_calendario');
    }
}
