<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaEquipo extends Model
{
    use HasFactory;

    protected $table = 'estadistica_equipos';

    protected $fillable = [
        'equipo_id',
        'calendario_id',
        'posesion',
        'regates_exito',
        'precision_tiros',
        'precision_pases',
        'recuperacion_balon',
        'tiros',
        'goles_esperados',
        'pases',
        'entradas',
        'entradas_exito',
        'recuperaciones',
        'atajadas',
        'faltas_cometidas',
        'fuera_de_juego',
        'tiros_esquina',
        'tiros_libres',
        'penales',
        'tarjetas_amarillas',
    ];

    // Relaciones
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function calendario()
    {
        return $this->belongsTo(Calendario::class);
    }
}
