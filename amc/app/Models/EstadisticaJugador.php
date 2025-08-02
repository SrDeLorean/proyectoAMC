<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Equipo;
use App\Models\Calendario;
use App\Models\TemporadaCompetencia;

class EstadisticaJugador extends Model
{
    use SoftDeletes;

    protected $table = 'estadistica_jugadores';

    protected $fillable = [
        'id_jugador',
        'id_equipo',
        'id_calendario',
        'id_temporadacompetencia',
        'posicion',
        'nombre',
        'calificacion',
        'goles',
        'asistencias',
        'tiros',
        'precision_tiros',
        'pases',
        'precision_pases',
        'regates',
        'tasa_exito_entradas',
        'fueras_de_juego',
        'faltas_cometidas',
        'posesion_ganada',
        'posesion_perdida',
        'minutos_jugados_vs_equipo',
        'distancia_total_vs_equipo',
        'distancia_carrera_vs_equipo',
        'procesado',
        'foto',
    ];

    // Relaciones
    public function jugador()
    {
        return $this->belongsTo(User::class, 'id_jugador');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }

    public function calendario()
    {
        return $this->belongsTo(Calendario::class, 'id_calendario');
    }

    public function temporadaCompetencia()
    {
        return $this->belongsTo(TemporadaCompetencia::class, 'id_temporadacompetencia');
    }
}
