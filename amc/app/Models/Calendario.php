<?php

// app/Models/Calendario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    protected $fillable = [
        'id_temporadacompetencia',
        'equipo_local_id',
        'equipo_visitante_id',
        'goles_equipo_local',
        'goles_equipo_visitante',
        'jornada',
        'fecha',
        'hora',
    ];

    public function temporadaCompetencia()
    {
        return $this->belongsTo(TemporadaCompetencia::class, 'id_temporadacompetencia');
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
}
