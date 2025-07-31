<?php

namespace App\Http\Controllers\Jugador;

use App\Http\Controllers\Controller;
use App\Models\EstadisticaJugador;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard del jugador con sus datos y estadísticas agrupadas.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener estadísticas del jugador con relaciones cargadas
        $estadisticas = EstadisticaJugador::with([
            'temporadaCompetencia.temporada',
            'temporadaCompetencia.competencia'
        ])
        ->where('id_jugador', $user->id)
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
                // Puedes agregar más métricas aquí si deseas
            ];
        })->values(); // resetea las claves

        return Inertia::render('Jugador/Dashboard', [
            'user' => $user->makeHidden(['password', 'remember_token']),
            'estadisticas' => $estadisticas,
        ]);
    }
}
