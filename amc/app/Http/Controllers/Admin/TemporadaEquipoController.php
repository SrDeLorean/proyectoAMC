<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TemporadaEquipo;
use App\Models\TemporadaCompetencia;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class TemporadaEquipoController extends Controller
{
    public function index()
    {
        $temporadaEquipos = TemporadaEquipo::with(['temporadaCompetencia', 'equipo'])->get();

        return Inertia::render('Admin/TemporadaEquipos/Index', [
            'temporadaEquipos' => $temporadaEquipos,
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Admin/TemporadaEquipos/Create', [
            'equipos' => Equipo::select('id', 'nombre')->get(),
            'id_temporadacompetencia' => $request->id_temporadacompetencia,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_equipo' => 'required|exists:equipos,id',
            'id_temporadacompetencia' => 'required|exists:temporada_competencias,id',
        ]);

        TemporadaEquipo::create($validated);

        return redirect()
            ->route('admin.temporada-competencias.show', $validated['id_temporadacompetencia'])
            ->with('success', 'Equipo afiliado correctamente.');
    }

    public function edit($id)
    {
        $temporadaEquipo = TemporadaEquipo::with(['temporadaCompetencia', 'equipo'])
            ->findOrFail($id)
            ->only(['id', 'id_equipo', 'id_temporadacompetencia']); // <-- Asegura estos campos

        $temporadaCompetencias = TemporadaCompetencia::select('id', 'nombre')->get();
        $equipos = Equipo::select('id', 'nombre')->get();

        return Inertia::render('Admin/TemporadaEquipos/Edit', [
            'temporadaEquipo' => $temporadaEquipo,
            'temporadaCompetencias' => $temporadaCompetencias,
            'equipos' => $equipos,
        ]);
    }

    public function update(Request $request, TemporadaEquipo $temporadaEquipo)
    {
        $validated = $request->validate([
            'id_equipo' => 'required|exists:equipos,id',
            'id_temporadacompetencia' => 'required|exists:temporada_competencias,id',
        ]);

        $temporadaEquipo->update($validated);

        Session::flash('success', 'TemporadaEquipo actualizado correctamente.');

        return redirect()
            ->route('admin.temporada-competencias.show', $validated['id_temporadacompetencia'])
            ->with('success', 'Equipo afiliado correctamente.');
    }

    public function destroy(TemporadaEquipo $temporadaEquipo)
    {
        $temporadaEquipo->delete();

        return redirect()
            ->route('admin.temporada-competencias.show', $temporadaEquipo->id_temporadacompetencia)
            ->with('success', 'TemporadaEquipo eliminado correctamente.');
    }
}
