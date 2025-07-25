<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Calendario;
use App\Models\Competencia;
use App\Models\TemporadaCompetencia;
use App\Models\TemporadaEquipo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompetenciaController extends Controller
{
    public function index(): Response
    {
        $competencias = Competencia::select('id', 'nombre', 'logo')->get()->toArray();

        return Inertia::render('Guest/Competencias/Index', [
            'competencias' => $competencias,
        ]);
    }

    public function show(Request $request, int $id): Response
    {
        $competencia = Competencia::findOrFail($id);

        $temporadasCompetencias = TemporadaCompetencia::where('id_competencia', $competencia->id)
            ->with('temporada')
            ->orderByDesc('fecha_inicio')
            ->get()
            ->toArray();

        $temporadaSeleccionadaId = $request->query('temporada_id');
        $temporadaCompetencia = collect($temporadasCompetencias)->firstWhere('id', $temporadaSeleccionadaId)
            ?? collect($temporadasCompetencias)->first();

        $temporadaCompetenciaId = $temporadaCompetencia['id'] ?? null;

        $equipos = $temporadaCompetenciaId
            ? TemporadaCompetencia::find($temporadaCompetenciaId)->equipos()->with('equipo')->get()->toArray()
            : [];

        $tablaClasificacion = $temporadaCompetenciaId
            ? TemporadaEquipo::with('equipo')
                ->where('id_temporadacompetencia', $temporadaCompetenciaId)
                ->orderByDesc('puntos')
                ->orderByDesc('diferencia_goles')
                ->orderByDesc('goles_a_favor')
                ->get()
                ->toArray()
            : [];

        $maximos = $this->getMaximosPorTemporadaCompetencia($temporadaCompetenciaId);
        $totw = $this->getTotwPorTemporadaCompetencia($temporadaCompetenciaId);

        // Obtener jornadas disponibles ordenadas asc
        $jornadasDisponibles = $temporadaCompetenciaId
            ? Calendario::where('id_temporadacompetencia', $temporadaCompetenciaId)
                ->pluck('jornada')
                ->unique()
                ->values()
                ->toArray()
            : [];

        // Jornada seleccionada desde request (puede ser null o '')
        $jornadaSeleccionada = $request->query('jornada', '');

        $query = Calendario::with(['equipoLocal', 'equipoVisitante'])
            ->where('id_temporadacompetencia', $temporadaCompetenciaId);

        // Solo filtrar jornada si viene con valor válido y no es string vacío
        if ($jornadaSeleccionada !== '' && $jornadaSeleccionada !== null) {
            $query->where('jornada', $jornadaSeleccionada);
        }

        $calendariosRaw = $query->orderBy('fecha', 'asc')->get();

        // No forzar selección de jornada, dejamos que venga '' para "Todas"

        // Mapear calendario para enviar solo datos necesarios
        $calendarios = $calendariosRaw->map(function ($partido) {
            return [
                'id' => $partido->id,
                'id_temporadacompetencia' => $partido->id_temporadacompetencia,
                'id_equipo_local' => $partido->id_equipo_local,
                'id_equipo_visitante' => $partido->id_equipo_visitante,
                'goles_equipo_local' => $partido->goles_equipo_local,
                'goles_equipo_visitante' => $partido->goles_equipo_visitante,
                'fecha' => $partido->fecha,
                'hora' => $partido->hora,
                'jornada' => $partido->jornada,
                'equipoLocal' => $partido->equipoLocal ? [
                    'id' => $partido->equipoLocal->id,
                    'nombre' => $partido->equipoLocal->nombre,
                    'logo' => $partido->equipoLocal->logo,
                ] : null,
                'equipoVisitante' => $partido->equipoVisitante ? [
                    'id' => $partido->equipoVisitante->id,
                    'nombre' => $partido->equipoVisitante->nombre,
                    'logo' => $partido->equipoVisitante->logo,
                ] : null,
            ];
        })->toArray();

        return Inertia::render('Guest/Competencias/Show', [
            'competencia' => $competencia->toArray(),
            'temporadasCompetencias' => $temporadasCompetencias,
            'temporadaCompetencia' => $temporadaCompetencia,
            'equipos' => $equipos,
            'clasificacion' => $tablaClasificacion,
            'maximos' => $maximos,
            'totw' => $totw,
            'calendario' => $calendarios,
            'jornadasDisponibles' => $jornadasDisponibles,
            'jornadaSeleccionada' => $jornadaSeleccionada,
        ]);
    }




    protected function getMaximosPorTemporadaCompetencia(?int $temporadaCompetenciaId): array
    {
        if (!$temporadaCompetenciaId) {
            return [];
        }
        // Aquí pones la lógica real para máximos goleadores, asistencias, etc.
        return [];
    }

    protected function getTotwPorTemporadaCompetencia(?int $temporadaCompetenciaId): array
    {
        if (!$temporadaCompetenciaId) {
            return [];
        }
        // Aquí pones la lógica real para TOTW (equipo de la semana)
        return [];
    }
}
