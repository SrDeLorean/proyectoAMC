<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TemporadaCompetencia;
use App\Models\Temporada;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use App\Models\TemporadaEquipo;

class TemporadaCompetenciaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 15);

        $query = TemporadaCompetencia::with(['temporada', 'competencia']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhereHas('temporada', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%{$search}%");
                })
                ->orWhereHas('competencia', function ($q3) use ($search) {
                    $q3->where('nombre', 'like', "%{$search}%");
                })
                ->orWhere('fecha_inicio', 'like', "%{$search}%")
                ->orWhere('fecha_termino', 'like', "%{$search}%");
            });
        }

        $temporadaCompetencias = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/TemporadaCompetencias/Index', [
            'temporadaCompetencias' => $temporadaCompetencias,
            'filters' => [
                'search' => $search,
                'perPage' => $perPage,
            ],
            'success' => session('success'),
        ]);
    }


    public function show(TemporadaCompetencia $temporadaCompetencia)
    {
        $temporadaCompetencia->load(['temporada', 'competencia']);

        $equipos = $temporadaCompetencia->equipos()->with('equipo')->get();

        return Inertia::render('Admin/TemporadaCompetencias/Show', [
            'temporadaCompetencia' => $temporadaCompetencia,
            'equipos' => $equipos,
        ]);
    }


    public function create()
    {
        // Traemos temporadas y competencias para los selects
        $temporadas = Temporada::all();
        $competencias = Competencia::all();

        return Inertia::render('Admin/TemporadaCompetencias/Create', [
            'temporadas' => $temporadas,
            'competencias' => $competencias,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'id_temporada' => 'required|exists:temporadas,id',
            'id_competencia' => 'required|exists:competencias,id',
            'formato' => 'nullable|string|in:liga,copa', // Formato opcional
            'fecha_inicio' => 'nullable|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        TemporadaCompetencia::create($validated);

        return redirect()
            ->route('admin.temporada-competencias.index')
            ->with('success', 'TemporadaCompetencia creada correctamente.');
    }

    public function edit(TemporadaCompetencia $temporadaCompetencia)
    {
        $temporadas = Temporada::all();
        $competencias = Competencia::all();

        return Inertia::render('Admin/TemporadaCompetencias/Edit', [
            'temporadaCompetencia' => [
                'id' => $temporadaCompetencia->id,
                'nombre' => $temporadaCompetencia->nombre,
                'id_temporada' => $temporadaCompetencia->id_temporada,
                'id_competencia' => $temporadaCompetencia->id_competencia,
                'formato' => $temporadaCompetencia->formato,
                'fecha_inicio' => $temporadaCompetencia->fecha_inicio?->format('Y-m-d'),
                'fecha_termino' => $temporadaCompetencia->fecha_termino?->format('Y-m-d'),
            ],
            'temporadas' => $temporadas,
            'competencias' => $competencias,
        ]);
    }

    public function update(Request $request, TemporadaCompetencia $temporadaCompetencia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'id_temporada' => 'required|exists:temporadas,id',
            'id_competencia' => 'required|exists:competencias,id',
            'formato' => 'nullable|string|in:liga,copa', // Formato opcional
            'fecha_inicio' => 'nullable|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $temporadaCompetencia->update($validated);

        Session::flash('success', 'TemporadaCompetencia actualizada correctamente.');

        return redirect()->route('admin.temporada-competencias.index');
    }

    public function destroy(TemporadaCompetencia $temporadaCompetencia)
    {
        $temporadaCompetencia->delete();

        return redirect()
            ->route('admin.temporada-competencias.index')
            ->with('success', 'TemporadaCompetencia eliminada correctamente.');
    }

    public function trashed()
    {
        $temporadaCompetencias = TemporadaCompetencia::onlyTrashed()->with(['temporada', 'competencia'])->get();

        return Inertia::render('Admin/TemporadaCompetencias/Trashed', [
            'temporadaCompetencias' => $temporadaCompetencias,
            'success' => session('success'),
        ]);
    }

    public function restore($id)
    {
        $temporadaCompetencia = TemporadaCompetencia::onlyTrashed()->findOrFail($id);
        $temporadaCompetencia->restore();

        return redirect()
            ->route('admin.temporada-competencias.trashed')
            ->with('success', 'TemporadaCompetencia restaurada correctamente.');
    }


}
