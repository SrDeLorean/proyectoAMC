<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Temporada;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class TemporadaController extends Controller
{
    public function index()
    {
        $temporadas = Temporada::all();

        return Inertia::render('Admin/Temporadas/Index', [
            'temporadas' => $temporadas,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Temporadas/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Temporada::create($request->only([
            'nombre',
        ]));

        return redirect()
            ->route('temporadas.index')
            ->with('success', 'Temporada creada correctamente.');
    }

    public function edit(Temporada $temporada)
    {
        return Inertia::render('Admin/Temporadas/Edit', [
            'temporada' => [
                'id' => $temporada->id,
                'nombre' => $temporada->nombre,
            ],
        ]);
    }

    public function update(Request $request, Temporada $temporada)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $temporada->update($validated);

        Session::flash('success', 'Temporada actualizada correctamente.');

        return redirect()->route('temporadas.index');
    }

    public function destroy(Temporada $temporada)
    {
        $temporada->delete();

        return redirect()
            ->route('temporadas.index')
            ->with('success', 'Temporada eliminada correctamente.');
    }

    public function trashed()
    {
        $temporadas = Temporada::onlyTrashed()->get();

        return Inertia::render('Admin/Temporadas/Trashed', [
            'temporadas' => $temporadas,
            'success' => session('success'),
        ]);
    }

    public function restore($id)
    {
        $temporada = Temporada::onlyTrashed()->findOrFail($id);
        $temporada->restore();

        return redirect()
            ->route('temporadas.trashed')
            ->with('success', 'Temporada restaurada correctamente.');
    }
}
