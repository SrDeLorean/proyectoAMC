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
}
