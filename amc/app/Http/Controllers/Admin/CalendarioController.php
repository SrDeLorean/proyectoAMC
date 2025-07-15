<?php

// app/Http/Controllers/CalendarioController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Calendario;
use App\Models\TemporadaCompetencia;
use App\Models\Temporada;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use App\Models\TemporadaEquipo;
use App\Models\Plantilla;
use App\Models\EstadisticaEquipo;
use Illuminate\Support\Facades\DB;


class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        $trashed = $request->boolean('trashed');
        $perPage = $request->integer('perPage', 10);

        $query = Calendario::query()
            ->with([
                'temporadaCompetencia.temporada',
                'temporadaCompetencia.competencia',
                'equipoLocal',
                'equipoVisitante',
            ]);

        // Aplicar filtros
        if ($request->filled('jornada')) {
            $query->where('jornada', $request->input('jornada'));
        }

        if ($request->filled('temporada')) {
            $query->whereHas('temporadaCompetencia.temporada', function ($q) use ($request) {
                $q->where('id', $request->input('temporada'));
            });
        }

        if ($request->filled('competencia')) {
            $query->whereHas('temporadaCompetencia.competencia', function ($q) use ($request) {
                $q->where('id', $request->input('competencia'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('equipoLocal', fn($q2) => $q2->where('nombre', 'like', "%$search%"))
                ->orWhereHas('equipoVisitante', fn($q2) => $q2->where('nombre', 'like', "%$search%"))
                ->orWhereHas('temporadaCompetencia.temporada', fn($q2) => $q2->where('nombre', 'like', "%$search%"))
                ->orWhereHas('temporadaCompetencia.competencia', fn($q2) => $q2->where('nombre', 'like', "%$search%"));
            });
        }

        // Papelera
        if ($trashed) {
            $query->onlyTrashed();
        }

        $calendarios = $query->orderBy('fecha')
            ->paginate($perPage)
            ->appends($request->all());

        return Inertia::render('Admin/Calendarios/Index', [
            'calendarios' => $calendarios,
            'jornadas' => Calendario::select('jornada')->distinct()->pluck('jornada')->sort()->values(),
            'temporadas' => Temporada::orderBy('nombre')->get(),
            'competencias' => Competencia::orderBy('nombre')->get(),
            'filters' => $request->only(['jornada', 'temporada', 'competencia', 'search', 'perPage']),
            'trashed' => $trashed,
            'success' => session('success'),
        ]);
    }

    public function show($id)
    {
        // Carga el calendario con relaciones necesarias
        $calendario = Calendario::with([
            'equipoLocal',
            'equipoVisitante',
            'temporadaCompetencia.temporada',
            'temporadaCompetencia.competencia',
            'equipoLocal.propietario',
            'equipoLocal.entrenador',
            'equipoVisitante.propietario',
            'equipoVisitante.entrenador',
        ])->findOrFail($id);

        // Tabla de clasificación
        $tablaClasificacion = TemporadaEquipo::with('equipo')
            ->where('id_temporadacompetencia', $calendario->id_temporadacompetencia)
            ->orderByDesc('puntos')
            ->orderByDesc('diferencia_goles')
            ->orderByDesc('goles_a_favor')
            ->get()
            ->toArray();

        // Obtener ID de ambos equipos
        $idEquipoLocal = $calendario->equipoLocal->id ?? null;
        $idEquipoVisitante = $calendario->equipoVisitante->id ?? null;

        // Obtener estadísticas si existen
        $estadisticaLocal = $idEquipoLocal
            ? EstadisticaEquipo::where('calendario_id', $id)
                ->where('equipo_id', $idEquipoLocal)
                ->first()
            : null;

        $estadisticaVisitante = $idEquipoVisitante
            ? EstadisticaEquipo::where('calendario_id', $id)
                ->where('equipo_id', $idEquipoVisitante)
                ->first()
            : null;

        // Helper para obtener plantilla
        $obtenerPlantilla = fn($idEquipo) => Plantilla::with('jugador')
            ->where('id_equipo', $idEquipo)
            ->get()
            ->map(fn($item) => [
                'numero' => $item->numero,
                'posicion' => $item->posicion,
                'jugador' => [
                    'id' => $item->jugador->id,
                    'id_ea' => $item->jugador->id_ea ?? 'Sin ID',
                    'foto' => $item->jugador->foto,
                ],
            ])
            ->toArray();

        // Obtener plantillas
        $plantillaLocal = $idEquipoLocal ? $obtenerPlantilla($idEquipoLocal) : [];
        $plantillaVisitante = $idEquipoVisitante ? $obtenerPlantilla($idEquipoVisitante) : [];

        // Normalizar logos
        $local = $calendario->equipoLocal;
        $visitante = $calendario->equipoVisitante;

        $local->logo = $local->logo ? (str_starts_with($local->logo, '/') ? $local->logo : '/' . $local->logo) : '/images/equipos/default-logo.png';
        $visitante->logo = $visitante->logo ? (str_starts_with($visitante->logo, '/') ? $visitante->logo : '/' . $visitante->logo) : '/images/equipos/default-logo.png';

        // Obtener dueño y entrenador (usuarios relacionados)
        $duenoLocal = $local->propietario ?? null;
        $entrenadorLocal = $local->entrenador ?? null;

        $duenoVisitante = $visitante->propietario ?? null;
        $entrenadorVisitante = $visitante->entrenador ?? null;

        // Preparar datos del equipo con dueño y entrenador
        $localData = [
            'id' => $local->id,
            'nombre' => $local->nombre,
            'logo' => $local->logo,
            'propietario' => $duenoLocal ? [
                'id' => $duenoLocal->id,
                'nombre' => $duenoLocal->name,
                'foto' => $duenoLocal->foto,
                'rol' => 'Dueño',
            ] : null,
            'entrenador' => $entrenadorLocal ? [
                'id' => $entrenadorLocal->id,
                'nombre' => $entrenadorLocal->name,
                'foto' => $entrenadorLocal->foto,
                'rol' => 'Entrenador',
            ] : null,
        ];

        $visitanteData = [
            'id' => $visitante->id,
            'nombre' => $visitante->nombre,
            'logo' => $visitante->logo,
            'propietario' => $duenoVisitante ? [
                'id' => $duenoVisitante->id,
                'nombre' => $duenoVisitante->name,
                'foto' => $duenoVisitante->foto,
                'rol' => 'Dueño',
            ] : null,
            'entrenador' => $entrenadorVisitante ? [
                'id' => $entrenadorVisitante->id,
                'nombre' => $entrenadorVisitante->name,
                'foto' => $entrenadorVisitante->foto,
                'rol' => 'Entrenador',
            ] : null,
        ];

        return Inertia::render('Admin/Calendarios/Show', [
            'calendario' => $calendario->toArray(),
            'tablaClasificacion' => $tablaClasificacion,
            'local' => $localData,
            'visitante' => $visitanteData,
            'plantillaLocal' => $plantillaLocal,
            'plantillaVisitante' => $plantillaVisitante,
            // Pasamos las estadísticas ya sea el objeto o null
            'estadistica_local' => $estadisticaLocal ? $estadisticaLocal->toArray() : null,
            'estadistica_visitante' => $estadisticaVisitante ? $estadisticaVisitante->toArray() : null,
        ]);
    }



    public function create()
    {
        return Inertia::render('Admin/Calendarios/Create', [
            'temporadaCompetencias' => TemporadaCompetencia::all(),
            'equipos' => TemporadaEquipo::with('equipo')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_temporadacompetencia' => 'required|exists:temporada_competencias,id',
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha' => 'nullable|date',
            'jornada' => 'nullable|integer|min:1',
        ]);

        Calendario::create($validated);

        return redirect()
            ->route('admin.calendarios.index')
            ->with('success', 'Partido agregado al calendario.');
    }

    public function edit(Calendario $calendario)
    {
        return Inertia::render('Admin/Calendarios/Edit', [
            'calendario' => [
                'id' => $calendario->id,
                'id_temporadacompetencia' => $calendario->id_temporadacompetencia,
                'equipo_local_id' => $calendario->equipo_local_id,
                'equipo_visitante_id' => $calendario->equipo_visitante_id,
                'fecha' => $calendario->fecha?->format('Y-m-d\TH:i'),
                'jornada' => $calendario->jornada,
            ],
            'temporadaCompetencias' => TemporadaCompetencia::all(),
            'equipos' => TemporadaEquipo::with('equipo')->get(),
        ]);
    }

    public function update(Request $request, Calendario $calendario)
    {
        // Si se están actualizando solo los goles (desde el modal)
        if ($request->has(['goles_equipo_local', 'goles_equipo_visitante'])) {
            $validated = $request->validate([
                'goles_equipo_local' => 'required|integer|min:0',
                'goles_equipo_visitante' => 'required|integer|min:0',
            ]);

            // Guardar valores previos
            $golesAntesLocal = $calendario->goles_equipo_local;
            $golesAntesVisitante = $calendario->goles_equipo_visitante;

            // Actualizar resultado en el modelo
            $calendario->update($validated);

            // Obtener equipos y registro en temporada_equipos
            $local = TemporadaEquipo::where([
                ['id_temporadacompetencia', $calendario->id_temporadacompetencia],
                ['id_equipo', $calendario->equipo_local_id],
            ])->first();

            $visitante = TemporadaEquipo::where([
                ['id_temporadacompetencia', $calendario->id_temporadacompetencia],
                ['id_equipo', $calendario->equipo_visitante_id],
            ])->first();

            // Restar solo si ya existía resultado previo
            if ($golesAntesLocal !== null && $golesAntesVisitante !== null) {
                self::restarResultadoPrevio($local, $visitante, $golesAntesLocal, $golesAntesVisitante);
            }

            // Sumar nuevo resultado
            self::sumarResultadoNuevo($local, $visitante, $validated['goles_equipo_local'], $validated['goles_equipo_visitante']);

            Session::flash('success', 'Resultado actualizado correctamente.');
            return back();
        }

        // Actualización completa desde formulario de edición
        $validated = $request->validate([
            'id_temporadacompetencia' => 'required|exists:temporada_competencias,id',
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha' => 'nullable|date',
            'jornada' => 'nullable|integer|min:1',
        ]);

        $calendario->update($validated);

        Session::flash('success', 'Partido actualizado correctamente.');
        return redirect()->route('admin.calendarios.index');
    }

private static function sumarResultadoNuevo($local, $visitante, $golesLocal, $golesVisitante)
{
    // Local
    $local->partidos_jugados += 1;
    $local->goles_a_favor += $golesLocal;
    $local->goles_en_contra += $golesVisitante;
    $local->diferencia_goles = $local->goles_a_favor - $local->goles_en_contra;

    // Visitante
    $visitante->partidos_jugados += 1;
    $visitante->goles_a_favor += $golesVisitante;
    $visitante->goles_en_contra += $golesLocal;
    $visitante->diferencia_goles = $visitante->goles_a_favor - $visitante->goles_en_contra;

    if ($golesLocal > $golesVisitante) {
        $local->victorias += 1;
        $local->puntos += 3;

        $visitante->derrotas += 1;
    } elseif ($golesLocal < $golesVisitante) {
        $visitante->victorias += 1;
        $visitante->puntos += 3;

        $local->derrotas += 1;
    } else {
        $local->empates += 1;
        $visitante->empates += 1;

        $local->puntos += 1;
        $visitante->puntos += 1;
    }

    $local->save();
    $visitante->save();
}

private static function restarResultadoPrevio($local, $visitante, $golesLocal, $golesVisitante)
{
    // Local
    $local->partidos_jugados = max(0, $local->partidos_jugados - 1);
    $local->goles_a_favor = max(0, $local->goles_a_favor - $golesLocal);
    $local->goles_en_contra = max(0, $local->goles_en_contra - $golesVisitante);
    $local->diferencia_goles = $local->goles_a_favor - $local->goles_en_contra;

    // Visitante
    $visitante->partidos_jugados = max(0, $visitante->partidos_jugados - 1);
    $visitante->goles_a_favor = max(0, $visitante->goles_a_favor - $golesVisitante);
    $visitante->goles_en_contra = max(0, $visitante->goles_en_contra - $golesLocal);
    $visitante->diferencia_goles = $visitante->goles_a_favor - $visitante->goles_en_contra;

    if ($golesLocal > $golesVisitante) {
        $local->victorias = max(0, $local->victorias - 1);
        $local->puntos = max(0, $local->puntos - 3);

        $visitante->derrotas = max(0, $visitante->derrotas - 1);
    } elseif ($golesLocal < $golesVisitante) {
        $visitante->victorias = max(0, $visitante->victorias - 1);
        $visitante->puntos = max(0, $visitante->puntos - 3);

        $local->derrotas = max(0, $local->derrotas - 1);
    } else {
        $local->empates = max(0, $local->empates - 1);
        $visitante->empates = max(0, $visitante->empates - 1);

        $local->puntos = max(0, $local->puntos - 1);
        $visitante->puntos = max(0, $visitante->puntos - 1);
    }

    $local->save();
    $visitante->save();
}


    public function destroy(Calendario $calendario)
    {
        $calendario->delete();

        return redirect()
            ->route('admin.calendarios.index')
            ->with('success', 'Partido eliminado.');
    }

    public function trashed()
    {
        $calendarios = Calendario::onlyTrashed()->with(['equipoLocal', 'equipoVisitante', 'temporadaCompetencia'])->get();

        return Inertia::render('Admin/Calendarios/Trashed', [
            'calendarios' => $calendarios,
            'success' => session('success'),
        ]);
    }

    public function restore($id)
    {
        $calendario = Calendario::onlyTrashed()->findOrFail($id);
        $calendario->restore();

        return redirect()
            ->route('admin.calendarios.trashed')
            ->with('success', 'Partido restaurado correctamente.');
    }

    public function generateByTemporadaCompetencia($id)
    {
        $temporadaCompetencia = TemporadaCompetencia::with('temporada')->findOrFail($id);

        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $id)
            ->pluck('id_equipo')
            ->toArray();

        $totalEquipos = count($equipos);

        if ($totalEquipos < 2) {
            return back()->with('error', 'Se requieren al menos 2 equipos para generar un calendario.');
        }

        $esImpar = $totalEquipos % 2 !== 0;
        if ($esImpar) {
            $equipos[] = null; // "bye"
            $totalEquipos++;
        }

        $jornadas = $totalEquipos - 1;
        $partidosPorJornada = $totalEquipos / 2;

        $partidos = [];

        for ($jornada = 1; $jornada <= $jornadas; $jornada++) {
            for ($i = 0; $i < $partidosPorJornada; $i++) {
                $local = $equipos[$i];
                $visitante = $equipos[$totalEquipos - 1 - $i];

                if ($local !== null && $visitante !== null) {
                    $partidos[] = [
                        'id_temporadacompetencia' => $id,
                        'equipo_local_id' => $local,
                        'equipo_visitante_id' => $visitante,
                        'jornada' => $jornada,
                    ];
                }
            }

            // Rotación correcta: deja fijo el primero, rota el resto a la derecha
            $pivot = array_splice($equipos, 1);         // Saca todo menos el primero
            array_unshift($pivot, array_pop($pivot));   // Rota a la derecha
            $equipos = array_merge([$equipos[0]], $pivot); // Une el primero con el resto rotado
        }

        // Ida y vuelta
        $partidosVuelta = [];
        foreach ($partidos as $p) {
            $partidosVuelta[] = [
                'id_temporadacompetencia' => $p['id_temporadacompetencia'],
                'equipo_local_id' => $p['equipo_visitante_id'],
                'equipo_visitante_id' => $p['equipo_local_id'],
                'jornada' => $p['jornada'] + $jornadas,
            ];
        }

        $todosPartidos = array_merge($partidos, $partidosVuelta);

        // Generar fechas y horas para cada jornada
        $fechaInicio = \Carbon\Carbon::parse($temporadaCompetencia->fecha_inicio);
        $diasPermitidos = ['Monday', 'Tuesday', 'Thursday'];
        $horas = ['23:00:00', '23:30:00'];

        $jornadaFechaHora = [];
        $jornadaActual = 1;
        $fechaActual = $fechaInicio->copy();

        while ($jornadaActual <= count($todosPartidos) / $partidosPorJornada) {
            if (in_array($fechaActual->englishDayOfWeek, $diasPermitidos)) {
                foreach ($horas as $hora) {
                    if ($jornadaActual > count($todosPartidos) / $partidosPorJornada) break;
                    $jornadaFechaHora[$jornadaActual] = [
                        'fecha' => $fechaActual->toDateString(),
                        'hora' => $hora
                    ];
                    $jornadaActual++;
                }
            }
            $fechaActual->addDay();
        }

        // Insertar partidos con fechas y horas asignadas
        foreach ($todosPartidos as &$partido) {
            $info = $jornadaFechaHora[$partido['jornada']] ?? ['fecha' => null, 'hora' => null];
            $partido['fecha'] = $info['fecha'];
            $partido['hora'] = $info['hora'];
        }

        Calendario::insert($todosPartidos);

        return back()->with('success', 'Calendario generado exitosamente con fechas y horas.');
    }


    public function generateSoloIdaByTemporadaCompetencia($id)
    {
        $temporadaCompetencia = TemporadaCompetencia::with('temporada')->findOrFail($id);

        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $id)
            ->pluck('id_equipo')
            ->toArray();

        $totalEquipos = count($equipos);

        if ($totalEquipos < 2) {
            return back()->with('error', 'Se requieren al menos 2 equipos para generar un calendario.');
        }

        $esImpar = $totalEquipos % 2 !== 0;
        if ($esImpar) {
            $equipos[] = null; // "bye"
            $totalEquipos++;
        }

        $jornadas = $totalEquipos - 1;
        $partidosPorJornada = $totalEquipos / 2;

        $partidos = [];

        for ($jornada = 1; $jornada <= $jornadas; $jornada++) {
            for ($i = 0; $i < $partidosPorJornada; $i++) {
                $local = $equipos[$i];
                $visitante = $equipos[$totalEquipos - 1 - $i];

                if ($local !== null && $visitante !== null) {
                    $partidos[] = [
                        'id_temporadacompetencia' => $id,
                        'equipo_local_id' => $local,
                        'equipo_visitante_id' => $visitante,
                        'jornada' => $jornada,
                    ];
                }
            }

            // Rotación round-robin (equipo 0 fijo)
            $pivot = array_splice($equipos, 1);         // Quita el primero
            array_unshift($pivot, array_pop($pivot));   // Rota a la derecha
            $equipos = array_merge([$equipos[0]], $pivot); // Junta fijo + rotados
        }

        // Asignar fechas y horas
        $fechaInicio = \Carbon\Carbon::parse($temporadaCompetencia->fecha_inicio);
        $diasPermitidos = ['Monday', 'Tuesday', 'Thursday'];
        $horas = ['23:00:00', '23:30:00'];

        $jornadaFechaHora = [];
        $jornadaActual = 1;
        $fechaActual = $fechaInicio->copy();

        while ($jornadaActual <= $jornadas) {
            if (in_array($fechaActual->englishDayOfWeek, $diasPermitidos)) {
                foreach ($horas as $hora) {
                    if ($jornadaActual > $jornadas) break;
                    $jornadaFechaHora[$jornadaActual] = [
                        'fecha' => $fechaActual->toDateString(),
                        'hora' => $hora,
                    ];
                    $jornadaActual++;
                }
            }
            $fechaActual->addDay();
        }

        // Asignar fecha y hora a cada partido
        foreach ($partidos as &$partido) {
            $info = $jornadaFechaHora[$partido['jornada']] ?? ['fecha' => null, 'hora' => null];
            $partido['fecha'] = $info['fecha'];
            $partido['hora'] = $info['hora'];
        }

        Calendario::insert($partidos);

        return back()->with('success', 'Calendario de solo ida generado exitosamente.');
    }




}
