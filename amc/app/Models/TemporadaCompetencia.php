<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasMany(EstadisticaEquipo::class, 'temporadaCompetencia_id');
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'id_competencia');
    }

    public function estadisticaJugadores()
    {
        return $this->hasMany(EstadisticaJugador::class, 'id_temporadaCompetencia');
    }

    public function traspasos()
    {
        return $this->hasMany(Traspaso::class, 'id_temporada_competencia');
    }

    /**
     * Comprueba si el periodo de fichajes está abierto actualmente.
     *
     * @return bool
     */
    public function fichajesAbiertos(): bool
    {
        if (!$this->fichajes_abiertos) {
            return false;
        }

        $now = now();

        if ($this->fichajes_inicio && $now->lt($this->fichajes_inicio)) {
            return false;
        }

        if ($this->fichajes_fin && $now->gt($this->fichajes_fin)) {
            return false;
        }

        return true;
    }

    // Eventos para cascada soft delete y restore

    protected static function booted()
    {
        static::deleting(function ($temporadaCompetencia) {
            if ($temporadaCompetencia->isForceDeleting()) {
                $temporadaCompetencia->temporadaEquipos()->withTrashed()->forceDelete();
                $temporadaCompetencia->calendarios()->withTrashed()->forceDelete();
                $temporadaCompetencia->estadisticaEquipos()->withTrashed()->forceDelete();
                $temporadaCompetencia->estadisticaJugadores()->withTrashed()->forceDelete();
                $temporadaCompetencia->traspasos()->withTrashed()->forceDelete();

                foreach ($temporadaCompetencia->temporadaEquipos()->withTrashed()->get() as $temporadaEquipo) {
                    $temporadaEquipo->temporadaPlantillas()->withTrashed()->forceDelete();
                }
            } else {
                $temporadaCompetencia->temporadaEquipos()->get()->each->delete();
                $temporadaCompetencia->calendarios()->get()->each->delete();
                $temporadaCompetencia->estadisticaEquipos()->get()->each->delete();
                $temporadaCompetencia->estadisticaJugadores()->get()->each->delete();
                $temporadaCompetencia->traspasos()->get()->each->delete();

                foreach ($temporadaCompetencia->temporadaEquipos as $temporadaEquipo) {
                    $temporadaEquipo->temporadaPlantillas()->get()->each->delete();
                }
            }
        });

        static::restoring(function ($temporadaCompetencia) {
            $temporadaCompetencia->temporadaEquipos()->onlyTrashed()->get()->each->restore();
            $temporadaCompetencia->calendarios()->onlyTrashed()->get()->each->restore();
            $temporadaCompetencia->estadisticaEquipos()->onlyTrashed()->get()->each->restore();
            $temporadaCompetencia->estadisticaJugadores()->onlyTrashed()->get()->each->restore();
            $temporadaCompetencia->traspasos()->onlyTrashed()->get()->each->restore();

            foreach ($temporadaCompetencia->temporadaEquipos()->onlyTrashed()->get() as $temporadaEquipo) {
                $temporadaEquipo->temporadaPlantillas()->onlyTrashed()->get()->each->restore();
            }
        });
    }

    protected function obtenerTemporadaCompetenciaActiva($id_equipo)
    {
        // Busca temporada_competencia que esté activa (no borrada) y relacionada al equipo
        $temporadaCompetencia = TemporadaCompetencia::where('id_equipo', $id_equipo)
            ->whereNull('deleted_at') // softdelete
            ->first();

        return $temporadaCompetencia ? $temporadaCompetencia->id : null;
    }
}
