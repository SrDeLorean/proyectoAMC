<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class PlantillaController extends Controller
{
    public function index()
    {
        $plantillas = Plantilla::with(['equipo', 'jugador'])->paginate(10);

        return Inertia::render('Admin/Plantillas/Index', [
            'plantillas' => $plantillas,
        ]);
    }

    public function create()
    {
        $equipos = Equipo::select('id', 'nombre')->get();

        // Obtener solo usuarios con rol "jugador"
        $jugadores = User::role('jugador')->select('id', 'name')->get();

        return Inertia::render('Admin/Plantillas/Create', [
            'equipos' => $equipos,
            'jugadores' => $jugadores,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_equipo'  => 'required|exists:equipos,id',
            'id_jugador' => 'required|exists:users,id',
            'posicion'   => 'required|string|max:255',
            'numero'     => 'required|integer|min:1',
        ]);

        Plantilla::create([
            'id_equipo'  => $request->id_equipo,
            'id_jugador' => $request->id_jugador,
            'posicion'   => $request->posicion,
            'numero'     => $request->numero,
        ]);

        return redirect()->route('admin.plantillas.index')->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(Plantilla $plantilla)
    {
        $equipos = Equipo::select('id', 'nombre')->get();
        $jugadores = User::role('jugador')->select('id', 'name')->get();

        return Inertia::render('Admin/Plantillas/Edit', [
            'plantilla' => $plantilla->load('equipo', 'jugador'),
            'equipos' => $equipos,
            'jugadores' => $jugadores,
        ]);
    }


    public function update(Request $request, Plantilla $plantilla)
    {
        $validated = $request->validate([
            'id_equipo'  => ['required', Rule::exists('equipos', 'id')],
            'id_jugador' => ['required', Rule::exists('users', 'id')],
            'posicion'   => 'required|string|max:255',
            'numero'     => 'required|integer|min:1',
        ]);

        $plantilla->update($validated);

        return redirect()->route('admin.plantillas.index')->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy(Plantilla $plantilla)
    {
        $plantilla->delete();

        return redirect()->route('admin.plantillas.index')->with('success', 'Plantilla eliminada correctamente.');
    }

    public function trashed()
    {
        $plantillas = Plantilla::onlyTrashed()->with(['equipo', 'jugador'])->paginate(10);

        return Inertia::render('Admin/Plantillas/Trashed', [
            'plantillas' => $plantillas,
        ]);
    }

    public function restore($id)
    {
        $plantilla = Plantilla::onlyTrashed()->findOrFail($id);
        $plantilla->restore();

        return redirect()->route('admin.plantillas.trashed')->with('success', 'Plantilla restaurada correctamente.');
    }

    public function forceDelete($id)
    {
        $plantilla = Plantilla::onlyTrashed()->findOrFail($id);
        $plantilla->forceDelete();

        return redirect()->route('admin.plantillas.trashed')->with('success', 'Plantilla eliminada permanentemente.');
    }
}
