<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadisticaEquipo extends Model
{
    use SoftDeletes;

    protected $table = 'estadistica_equipos';

    protected $fillable = [
        'id_equipo',
        'id_calendario',
        'id_temporadaCompetencia',

        'posesion',
        'tasa_exito_regates',
        'precision_tiros',
        'precision_pases',

        'recuperacion_balon',
        'tiros',
        'goles_esperados',
        'pases',
        'entradas',
        'entradas_con_exito',
        'recuperaciones',
        'atajadas',
        'faltas_cometidas',
        'fuera_de_juego',
        'tiros_de_esquina',
        'tiros_libres',
        'penales',
        'tarjetas_amarillas',

        'procesado',
        'foto',
    ];

    protected $casts = [
        'procesado' => 'boolean',
        'goles_esperados' => 'float',
        'posesion' => 'integer',
        'tasa_exito_regates' => 'integer',
        'precision_tiros' => 'integer',
        'precision_pases' => 'integer',
        'recuperacion_balon' => 'integer',
        'tiros' => 'integer',
        'pases' => 'integer',
        'entradas' => 'integer',
        'entradas_con_exito' => 'integer',
        'recuperaciones' => 'integer',
        'atajadas' => 'integer',
        'faltas_cometidas' => 'integer',
        'fuera_de_juego' => 'integer',
        'tiros_de_esquina' => 'integer',
        'tiros_libres' => 'integer',
        'penales' => 'integer',
        'tarjetas_amarillas' => 'integer',
    ];

    // Relaciones

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
        return $this->belongsTo(TemporadaCompetencia::class, 'id_temporadaCompetencia');
    }
}
