<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendario;
use App\Models\TemporadaCompetencia;
use App\Models\Temporada;
use App\Models\Competencia;
use App\Models\TemporadaEquipo;
use App\Models\Plantilla;
use App\Models\EstadisticaEquipo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use App\Services\CalendarioService;
use App\Services\CalendarioCopaService;


class CalendarioController extends Controller
{
    protected $calendarioService, $calendarioCopaService;

    public function __construct(CalendarioService $calendarioService, CalendarioCopaService $calendarioCopaService)
    {
        $this->calendarioService = $calendarioService;
        $this->calendarioCopaService = $calendarioCopaService;
    }

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

        $tablaClasificacion = TemporadaEquipo::with('equipo')
            ->where('id_temporadacompetencia', $calendario->id_temporadacompetencia)
            ->orderByDesc('puntos')
            ->orderByDesc('diferencia_goles')
            ->orderByDesc('goles_a_favor')
            ->get()
            ->toArray();

        $idEquipoLocal = $calendario->equipoLocal->id ?? null;
        $idEquipoVisitante = $calendario->equipoVisitante->id ?? null;

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

        $plantillaLocal = $idEquipoLocal ? $obtenerPlantilla($idEquipoLocal) : [];
        $plantillaVisitante = $idEquipoVisitante ? $obtenerPlantilla($idEquipoVisitante) : [];

        $local = $calendario->equipoLocal;
        $visitante = $calendario->equipoVisitante;

        $local->logo = $local->logo ? (str_starts_with($local->logo, '/') ? $local->logo : '/' . $local->logo) : '/images/equipos/default-logo.png';
        $visitante->logo = $visitante->logo ? (str_starts_with($visitante->logo, '/') ? $visitante->logo : '/' . $visitante->logo) : '/images/equipos/default-logo.png';

        $duenoLocal = $local->propietario ?? null;
        $entrenadorLocal = $local->entrenador ?? null;

        $duenoVisitante = $visitante->propietario ?? null;
        $entrenadorVisitante = $visitante->entrenador ?? null;

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
            'id_equipo_local' => 'required|exists:equipos,id|different:id_equipo_visitante',
            'id_equipo_visitante' => 'required|exists:equipos,id',
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
                'id_equipo_local' => $calendario->id_equipo_local,
                'id_equipo_visitante' => $calendario->id_equipo_visitante,
                'fecha' => $calendario->fecha?->format('Y-m-d\TH:i'),
                'jornada' => $calendario->jornada,
            ],
            'temporadaCompetencias' => TemporadaCompetencia::all(),
            'equipos' => TemporadaEquipo::with('equipo')->get(),
        ]);
    }

    public function update(Request $request, Calendario $calendario)
    {
        if ($request->has(['goles_equipo_local', 'goles_equipo_visitante'])) {
            $validated = $request->validate([
                'goles_equipo_local' => 'required|integer|min:0',
                'goles_equipo_visitante' => 'required|integer|min:0',
            ]);

            $golesAntesLocal = $calendario->goles_equipo_local;
            $golesAntesVisitante = $calendario->goles_equipo_visitante;

            $calendario->update($validated);

            $local = TemporadaEquipo::where([
                ['id_temporadacompetencia', $calendario->id_temporadacompetencia],
                ['id_equipo', $calendario->id_equipo_local],
            ])->first();

            $visitante = TemporadaEquipo::where([
                ['id_temporadacompetencia', $calendario->id_temporadacompetencia],
                ['id_equipo', $calendario->id_equipo_visitante],
            ])->first();

            if ($golesAntesLocal !== null && $golesAntesVisitante !== null) {
                self::restarResultadoPrevio($local, $visitante, $golesAntesLocal, $golesAntesVisitante);
            }

            self::sumarResultadoNuevo($local, $visitante, $validated['goles_equipo_local'], $validated['goles_equipo_visitante']);

            Session::flash('success', 'Resultado actualizado correctamente.');
            return back();
        }

        $validated = $request->validate([
            'id_temporadacompetencia' => 'required|exists:temporada_competencias,id',
            'id_equipo_local' => 'required|exists:equipos,id|different:id_equipo_visitante',
            'id_equipo_visitante' => 'required|exists:equipos,id',
            'fecha' => 'nullable|date',
            'jornada' => 'nullable|integer|min:1',
        ]);

        $calendario->update($validated);

        Session::flash('success', 'Partido actualizado correctamente.');
        return redirect()->route('admin.calendarios.index');
    }

    private static function sumarResultadoNuevo($local, $visitante, $golesLocal, $golesVisitante)
    {
        $local->partidos_jugados += 1;
        $local->goles_a_favor += $golesLocal;
        $local->goles_en_contra += $golesVisitante;
        $local->diferencia_goles = $local->goles_a_favor - $local->goles_en_contra;

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
        $local->partidos_jugados = max(0, $local->partidos_jugados - 1);
        $local->goles_a_favor = max(0, $local->goles_a_favor - $golesLocal);
        $local->goles_en_contra = max(0, $local->goles_en_contra - $golesVisitante);

        $visitante->partidos_jugados = max(0, $visitante->partidos_jugados - 1);
        $visitante->goles_a_favor = max(0, $visitante->goles_a_favor - $golesVisitante);
        $visitante->goles_en_contra = max(0, $visitante->goles_en_contra - $golesLocal);

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

        $local->diferencia_goles = $local->goles_a_favor - $local->goles_en_contra;
        $visitante->diferencia_goles = $visitante->goles_a_favor - $visitante->goles_en_contra;

        $local->save();
        $visitante->save();
    }

    public function destroy(Calendario $calendario)
    {
        $calendario->delete();

        return redirect()->route('admin.calendarios.index')->with('success', 'Partido eliminado correctamente.');
    }

    public function trashed()
    {
        $calendarios = Calendario::onlyTrashed()->paginate(10);

        return Inertia::render('Admin/Calendarios/Trashed', [
            'calendarios' => $calendarios,
        ]);
    }

    public function restore($id)
    {
        $calendario = Calendario::onlyTrashed()->findOrFail($id);
        $calendario->restore();

        return redirect()->route('admin.calendarios.index')->with('success', 'Partido restaurado correctamente.');
    }

    public function generateByTemporadaCompetencia($id)
    {
        $result = $this->calendarioService->generateCalendar($id, true);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', $result['message']);
    }

    public function generateSoloIdaByTemporadaCompetencia($id)
    {
        $result = $this->calendarioService->generateCalendar($id, false);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', $result['message']);
    }

    public function generateCopaDirecta($id)
    {
        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $id)
            ->pluck('id_equipo')
            ->toArray();

        // Obtener la fecha de inicio de la temporada competencia
        $temporadaCompetencia = TemporadaCompetencia::find($id);
        $fechaInicio = $temporadaCompetencia?->fecha_inicio ?? now();

        $resultado = $this->calendarioCopaService->generarEmparejamientoDirecto($equipos, $id, $fechaInicio);

        if (isset($resultado['error'])) {
            return back()->with('error', $resultado['error']);
        }

        return back()->with('success', $resultado['message']);
    }

    /**
     * Generar calendario copa con ventaja
     */
    public function generateCopaConVentaja($id)
    {
        $equipos = TemporadaEquipo::where('id_temporadacompetencia', $id)
            ->pluck('id_equipo')
            ->toArray();

        // Obtener la fecha de inicio de la temporada competencia
        $temporadaCompetencia = TemporadaCompetencia::find($id);
        $fechaInicio = $temporadaCompetencia?->fecha_inicio ?? now();

        $resultado = $this->calendarioCopaService->generarConVentaja($equipos, $id, $fechaInicio);

        if (isset($resultado['error'])) {
            return back()->with('error', $resultado['error']);
        }

        return back()->with('success', $resultado['message']);
    }
}
