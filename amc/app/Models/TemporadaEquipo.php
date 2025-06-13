<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemporadaEquipo extends Model
{
    use HasFactory;

    protected $fillable = ['id_temporadacompetencia', 'id_equipo'];

    public function temporadaCompetencia()
    {
        return $this->belongsTo(TemporadaCompetencia::class, 'id_temporadacompetencia');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo'); // Asumiendo que existe un modelo `Equipo`
    }
}
