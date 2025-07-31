<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\EstadisticaJugador;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Muestra los datos del entrenador y resumen de estadísticas de sus jugadores.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $entrenador = Auth::user();

        // Obtener los equipos que dirige (usando id_usuario2)
        $equipos = $entrenador->equiposComoEntrenador()->pluck('id');

        // Obtener estadísticas de jugadores en esos equipos
        $estadisticas = EstadisticaJugador::with([
            'temporadaCompetencia.temporada',
            'temporadaCompetencia.competencia'
        ])
        ->whereIn('id_equipo', $equipos)
        ->get()
        ->groupBy(function ($estadistica) {
            return optional($estadistica->temporadaCompetencia)->id;
        })
        ->map(function ($items) {
            $primera = $items->first();

            return [
                'temporada_competencia_id' => $primera->temporadaCompetencia->id ?? null,
                'temporada' => $primera->temporadaCompetencia->temporada->nombre ?? 'Sin temporada',
                'competencia' => $primera->temporadaCompetencia->competencia->nombre ?? 'Sin competencia',
                'partidos_jugados' => $items->count(),
                'goles' => $items->sum('goles'),
                'asistencias' => $items->sum('asistencias'),
                'minutos_jugados' => $items->sum('minutos_jugados_vs_equipo'),
                'calificacion_promedio' => round($items->avg('calificacion'), 2),
            ];
        })->values();

        return Inertia::render('Entrenador/Dashboard', [
            'user' => $entrenador->makeHidden(['password', 'remember_token']),
            'estadisticas' => $estadisticas,
        ]);
    }
}
