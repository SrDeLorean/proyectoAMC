<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class CompetenciaController extends Controller
{
    public function index()
    {
        $competencias = Competencia::all();

        return Inertia::render('Competencias/Index', [
            'competencias' => $competencias,
        ]);
    }

    public function create()
    {
        return Inertia::render('Competencias/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Competencia::create($request->only([
            'nombre',
        ]));

        return redirect()
            ->route('competencias.index')
            ->with('success', 'Competencia creada correctamente.');
    }

    public function edit(Competencia $competencia)
    {
        return Inertia::render('Competencias/Edit', [
            'competencia' => [
                'id' => $competencia->id,
                'nombre' => $competencia->nombre,
            ],
        ]);
    }

    public function update(Request $request, Competencia $competencia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $competencia->update($validated);

        Session::flash('success', 'Competencia actualizada correctamente.');

        return redirect()->route('competencias.index');
    }

    public function destroy(Competencia $competencia)
    {
        $competencia->delete();

        return redirect()
            ->route('competencias.index')
            ->with('success', 'Competencia eliminada correctamente.');
    }

    public function trashed()
    {
        $competencias = Competencia::onlyTrashed()->get();

        return Inertia::render('Competencias/Trashed', [
            'competencias' => $competencias,
            'success' => session('success'),
        ]);
    }

    public function restore($id)
    {
        $competencia = Competencia::onlyTrashed()->findOrFail($id);
        $competencia->restore();

        return redirect()
            ->route('competencias.trashed')
            ->with('success', 'Competencia restaurada correctamente.');
    }
}
