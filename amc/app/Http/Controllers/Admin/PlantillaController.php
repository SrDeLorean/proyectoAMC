<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class PlantillaController extends Controller
{
    public function index()
    {
        $plantillas = Plantilla::with(['equipo', 'jugador'])->paginate(10);

        return Inertia::render('Admin/Plantillas/Index', [
            'plantillas' => $plantillas,
            'success' => Session::get('success'),
        ]);
    }

    public function create()
    {
        $equipos = Equipo::select('id', 'nombre')->get();

        $jugadores = User::where('role', 'jugador')->select('id', 'name')->get();

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

        Session::flash('success', 'Plantilla creada correctamente.');

        return redirect()->route('admin.plantillas.index');
    }

    public function edit(Plantilla $plantilla)
    {
        $equipos = Equipo::select('id', 'nombre')->get();

        $jugadores = User::where('role', 'jugador')->select('id', 'name')->get();

        return Inertia::render('Admin/Plantillas/Edit', [
            'plantilla' => $plantilla->load('equipo', 'jugador'),
            'equipos' => $equipos,
            'jugadores' => $jugadores,
            'success' => Session::get('success'),
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

        Session::flash('success', 'Plantilla actualizada correctamente.');

        return redirect()->route('admin.plantillas.index');
    }

    public function destroy(Plantilla $plantilla)
    {
        $plantilla->delete();

        Session::flash('success', 'Plantilla eliminada correctamente.');

        return redirect()->route('admin.plantillas.index');
    }

    public function trashed()
    {
        $plantillas = Plantilla::onlyTrashed()->with(['equipo', 'jugador'])->paginate(10);

        return inertia('Admin/Plantillas/Trashed', [
            'plantillas' => $plantillas,
            'success' => session('success')
        ]);
    }

    public function restore($id)
    {
        $plantilla = Plantilla::onlyTrashed()->findOrFail($id);
        $plantilla->restore();

        Session::flash('success', 'Plantilla restaurada correctamente.');

        return redirect()->route('admin.plantillas.trashed');
    }

}
