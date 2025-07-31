<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemporadaCompetencia extends Model
{
    use SoftDeletes;

    protected $table = 'temporada_competencias';

    protected $fillable = [
        'nombre',
        'id_temporada',
        'id_competencia',
        'formato',
        'fecha_inicio',
        'fecha_termino',

        // Campos para fichajes
        'fichajes_abiertos',
        'fichajes_inicio',
        'fichajes_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_termino' => 'date',
        'fichajes_abiertos' => 'boolean',
        'fichajes_inicio' => 'datetime',
        'fichajes_fin' => 'datetime',
    ];

    // Relaciones
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'temporada_equipos', 'id_temporadacompetencia', 'id_equipo');
    }

    public function temporadaEquipos()
    {
        return $this->hasMany(TemporadaEquipo::class, 'id_temporadacompetencia');
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada');
    }

    public function calendarios()
    {
        return $this->hasMany(Calendario::class, 'id_temporadacompetencia');
    }

    public function estadisticaEquipos()
    {
        return $this->hasMany(EstadisticaEquipo::class, 'id_temporadacompetencia');
    }

    public function estadisticaJugadores()
    {
        return $this->hasMany(EstadisticaJugador::class, 'id_temporadacompetencia');
    }

    public function traspasos()
    {
        // RELACIÓN CORREGIDA: usa 'id_temporadacompetencia' como clave foránea
        return $this->hasMany(Traspaso::class, 'id_temporadacompetencia');
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'id_competencia');
    }

    public function fichajesAbiertos(): bool
    {
        if (!$this->fichajes_abiertos) return false;

        $now = now();

        if ($this->fichajes_inicio && $now->lt($this->fichajes_inicio)) return false;
        if ($this->fichajes_fin && $now->gt($this->fichajes_fin)) return false;

        return true;
    }

    // Cascada SoftDelete y Restore
    protected static function booted()
    {
        static::deleting(function ($tc) {
            if ($tc->isForceDeleting()) {
                $tc->temporadaEquipos()->withTrashed()->forceDelete();
                $tc->calendarios()->withTrashed()->forceDelete();
                $tc->estadisticaEquipos()->withTrashed()->forceDelete();
                $tc->estadisticaJugadores()->withTrashed()->forceDelete();
                $tc->traspasos()->withTrashed()->forceDelete();

                foreach ($tc->temporadaEquipos()->withTrashed()->get() as $te) {
                    $te->temporadaPlantillas()->withTrashed()->forceDelete();
                }
            } else {
                $tc->temporadaEquipos->each->delete();
                $tc->calendarios->each->delete();
                $tc->estadisticaEquipos->each->delete();
                $tc->estadisticaJugadores->each->delete();
                $tc->traspasos->each->delete();

                foreach ($tc->temporadaEquipos as $te) {
                    $te->temporadaPlantillas->each->delete();
                }
            }
        });

        static::restoring(function ($tc) {
            $tc->temporadaEquipos()->onlyTrashed()->get()->each->restore();
            $tc->calendarios()->onlyTrashed()->get()->each->restore();
            $tc->estadisticaEquipos()->onlyTrashed()->get()->each->restore();
            $tc->estadisticaJugadores()->onlyTrashed()->get()->each->restore();
            $tc->traspasos()->onlyTrashed()->get()->each->restore();

            foreach ($tc->temporadaEquipos()->onlyTrashed()->get() as $te) {
                $te->temporadaPlantillas()->onlyTrashed()->get()->each->restore();
            }
        });
    }

    // Utilidad para buscar activa por equipo
    protected function obtenerTemporadaCompetenciaActiva($id_equipo)
    {
        return self::where('id_equipo', $id_equipo)
            ->whereNull('deleted_at')
            ->value('id');
    }
}
