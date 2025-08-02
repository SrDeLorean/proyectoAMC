<?php

namespace App\Services;

use App\Models\Equipo;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaPlantilla;
use App\Models\Traspaso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class TraspasoService
{
    /**
     * Realiza el traspaso de un jugador entre equipos para una temporada_competencia.
     *
     * @param int $idJugador
     * @param int|null $idEquipoOrigen Puede ser null si viene libre
     * @param int $idEquipoDestino
     * @param int $idTemporadaCompetencia
     * @param int $idUsuarioRealizador Admin o entrenador que realiza el traspaso
     * @param string|null $motivo
     *
     * @return Traspaso
     * @throws Exception
     */
    public function realizarTraspaso(
        int $idJugador,
        ?int $idEquipoOrigen,
        int $idEquipoDestino,
        int $idTemporadaCompetencia,
        int $idUsuarioRealizador,
        ?string $motivo = null
    ): Traspaso {
        return DB::transaction(function () use (
            $idJugador,
            $idEquipoOrigen,
            $idEquipoDestino,
            $idTemporadaCompetencia,
            $idUsuarioRealizador,
            $motivo
        ) {
            $temporadaCompetencia = TemporadaCompetencia::findOrFail($idTemporadaCompetencia);

            if (!$temporadaCompetencia->fichajesAbiertos()) {
                throw new Exception("El periodo de fichajes para esta temporada competencia está cerrado.");
            }

            $jugador = User::findOrFail($idJugador);
            $equipoDestino = Equipo::findOrFail($idEquipoDestino);
            $equipoOrigen = $idEquipoOrigen ? Equipo::findOrFail($idEquipoOrigen) : null;

            // Elimina el jugador de la plantilla de equipo origen en esta temporada competencia (soft delete)
            if ($equipoOrigen) {
                $temporadaEquipoOrigen = $equipoOrigen->temporadaEquipos()
                    ->where('id_temporada_competencia', $idTemporadaCompetencia)
                    ->first();

                if ($temporadaEquipoOrigen) {
                    TemporadaPlantilla::where('id_jugador', $idJugador)
                        ->where('id_temporada_equipo', $temporadaEquipoOrigen->id)
                        ->delete();
                }
            }

            // Añade el jugador a la plantilla del equipo destino en esta temporada competencia
            $temporadaEquipoDestino = $equipoDestino->temporadaEquipos()
                ->where('id_temporada_competencia', $idTemporadaCompetencia)
                ->firstOrFail();

            // Aquí puedes ajustar rol, posición y número según tu lógica o parámetros
            TemporadaPlantilla::updateOrCreate(
                [
                    'id_jugador' => $idJugador,
                    'id_temporada_equipo' => $temporadaEquipoDestino->id,
                ],
                [
                    'rol' => 'jugador',  // ejemplo genérico
                    'posicion' => 'N/A',
                    'numero' => 0,
                    'deleted_at' => null,
                ]
            );

            // Registra el traspaso
            return Traspaso::create([
                'id_jugador' => $idJugador,
                'id_origen' => $idEquipoOrigen,
                'id_destino' => $idEquipoDestino,
                'id_temporada_competencia' => $idTemporadaCompetencia,
                'fecha_traspaso' => Carbon::now(),
                'motivo' => $motivo,
                'realizado_por' => $idUsuarioRealizador,
            ]);
        });
    }
}
