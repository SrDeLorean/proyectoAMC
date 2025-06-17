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

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        // Filtros enviados por query string
        $temporadaId = $request->query('temporada');
        $competenciaId = $request->query('competencia');
        $jornada = $request->query('jornada');
        $fornada = $request->query('fornada');

        // Consulta con relaciones
        $query = Calendario::with([
            'equipoLocal',
            'equipoVisitante',
            'temporadaCompetencia.temporada',
            'temporadaCompetencia.competencia',
        ]);

        if ($temporadaId) {
            $query->whereHas('temporadaCompetencia.temporada', function ($q) use ($temporadaId) {
                $q->where('id', $temporadaId);
            });
        }

        if ($competenciaId) {
            $query->whereHas('temporadaCompetencia.competencia', function ($q) use ($competenciaId) {
                $q->where('id', $competenciaId);
            });
        }

        if ($jornada) {
            $query->where('jornada', $jornada);
        }

        if ($fornada) {
            $query->where('fornada', $fornada);
        }

        $calendarios = $query->get();

        // Para llenar selects (temporadas, competencias, fornadas)
        $temporadas = Temporada::all();
        $competencias = Competencia::all();

        // Suponiendo que jornadas es un array de valores únicos de la DB
        $jornadas = Calendario::select('jornada')->distinct()->pluck('jornada')->toArray();

        return Inertia::render('Admin/Calendarios/Index', [
            'calendarios' => $calendarios,
            'temporadas' => $temporadas,
            'competencias' => $competencias,
            'jornadas' => $jornadas,
            'success' => session('success'),
            'trashed' => false,
        ]);
    }


    public function show(Calendario $calendario)
    {
        $calendario->load(['equipoLocal', 'equipoVisitante', 'temporadaCompetencia']);

        $tablaClasificacion = $calendario->temporadaCompetencia
            ? $calendario->temporadaCompetencia->obtenerTablaClasificacion()
            : collect();

        return Inertia::render('Admin/Calendarios/Show', [
            'calendario' => $calendario,
            'tablaClasificacion' => $tablaClasificacion,
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
            ->route('calendarios.index')
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
        $validated = $request->validate([
            'id_temporadacompetencia' => 'required|exists:temporada_competencias,id',
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha' => 'nullable|date',
            'jornada' => 'nullable|integer|min:1',
        ]);

        $calendario->update($validated);

        Session::flash('success', 'Partido actualizado correctamente.');

        return redirect()->route('calendarios.index');
    }

    public function destroy(Calendario $calendario)
    {
        $calendario->delete();

        return redirect()
            ->route('calendarios.index')
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
            ->route('calendarios.trashed')
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
