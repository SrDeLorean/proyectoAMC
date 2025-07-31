<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\TemporadaEquipo;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Equipo;

class CompetenciaController extends Controller
{
    public function index()
{
    $usuario = Auth::user();

    // Buscar el equipo donde el usuario es propietario o entrenador
    $equipo = Equipo::where(function ($q) use ($usuario) {
        $q->where('id_usuario', $usuario->id)
          ->orWhere('id_usuario2', $usuario->id);
    })->first();

    // Si no tiene equipo asignado, retornar sin resultados
    if (!$equipo) {
        return Inertia::render('Entrenador/Competencias/Index', [
            'activos' => [],
            'inactivos' => [],
        ]);
    }

    // Obtener competencias activas del equipo
    $activos = TemporadaEquipo::with([
            'temporadaCompetencia.competencia',
            'temporadaCompetencia.temporada',
        ])
        ->where('id_equipo', $equipo->id)
        ->whereNull('deleted_at')
        ->whereHas('temporadaCompetencia', function ($q) {
            $q->whereNull('deleted_at');
        })
        ->get();

    // Obtener competencias inactivas del equipo
    $inactivos = TemporadaEquipo::withTrashed()
        ->with([
            'temporadaCompetencia' => function ($q) {
                $q->withTrashed()->with(['competencia', 'temporada']);
            }
        ])
        ->where('id_equipo', $equipo->id)
        ->where(function ($query) {
            $query->whereNotNull('deleted_at') // Eliminado en temporada_equipos
                  ->orWhereHas('temporadaCompetencia', function ($q) {
                      $q->onlyTrashed(); // Eliminado en temporada_competencias
                  });
        })
        ->get();

    return Inertia::render('Entrenador/Competencias/Index', [
        'activos' => $activos,
        'inactivos' => $inactivos,
    ]);
}


    public function show($id)
    {
        $usuario = Auth::user();

        $temporadaEquipo = TemporadaEquipo::withTrashed()
            ->with([
                'temporadaCompetencia' => function ($query) {
                    $query->withTrashed()->with(['competencia', 'temporada']);
                },
                'equipo' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->where('id', $id)
            ->whereIn('id_equipo', $usuario->equiposComoEntrenador()->pluck('id'))
            ->firstOrFail();

        $competencia = optional($temporadaEquipo->temporadaCompetencia->competencia);
        $temporada = optional($temporadaEquipo->temporadaCompetencia->temporada);

        $torneo = [
            'id' => $temporadaEquipo->id,
            'id_equipo' => $temporadaEquipo->id_equipo,
            'competencia' => [
                'nombre' => $competencia->nombre,
                'logo' => $competencia->logo,
            ],
            'temporada' => [
                'nombre' => $temporada->nombre,
            ],
        ];

        $idEquipoUsuario = $temporadaEquipo->id_equipo;

        $partidos = Calendario::with([
                'equipoLocal' => fn ($q) => $q->select('id', 'nombre', 'logo')->withTrashed(),
                'equipoVisitante' => fn ($q) => $q->select('id', 'nombre', 'logo')->withTrashed(),
            ])
            ->where('id_temporadacompetencia', $temporadaEquipo->id_temporadacompetencia)
            ->where(function ($q) use ($idEquipoUsuario) {
                $q->where('id_equipo_local', $idEquipoUsuario)
                  ->orWhere('id_equipo_visitante', $idEquipoUsuario);
            })
            ->orderBy('jornada')
            ->orderBy('fecha')
            ->get()
            ->map(function ($partido) {
                return [
                    'id' => $partido->id,
                    'jornada' => $partido->jornada,
                    'fecha' => $partido->fecha,
                    'equipo_local' => $partido->equipoLocal ? [
                        'id' => $partido->equipoLocal->id,
                        'nombre' => $partido->equipoLocal->nombre,
                        'logo' => $partido->equipoLocal->logo,
                    ] : null,
                    'equipo_visitante' => $partido->equipoVisitante ? [
                        'id' => $partido->equipoVisitante->id,
                        'nombre' => $partido->equipoVisitante->nombre,
                        'logo' => $partido->equipoVisitante->logo,
                    ] : null,
                    'goles_equipo_local' => $partido->goles_equipo_local,
                    'goles_equipo_visitante' => $partido->goles_equipo_visitante,
                    'foto_rendimiento_local' => $partido->foto_rendimiento_local,
                    'foto_lista_id_local' => $partido->foto_lista_id_local,
                    'foto_rendimiento_jugadores_local' => $partido->foto_rendimiento_jugadores_local,
                    'foto_rendimiento_visitante' => $partido->foto_rendimiento_visitante,
                    'foto_lista_id_visitante' => $partido->foto_lista_id_visitante,
                    'foto_rendimiento_jugadores_visitante' => $partido->foto_rendimiento_jugadores_visitante,
                ];
            });

        return Inertia::render('Entrenador/Competencias/Show', [
            'torneo' => $torneo,
            'partidos' => $partidos,
        ]);
    }
}
