<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use App\Models\Equipo;
use App\Models\User;
use App\Models\Formacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PlantillaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Plantilla::with(['jugador', 'equipo']);

        if ($search) {
            $query->whereHas('jugador', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('equipo', fn($q) => $q->where('nombre', 'like', "%{$search}%"))
                ->orWhere('posicion', 'like', "%{$search}%")
                ->orWhere('numero', 'like', "%{$search}%");
        }

        $plantillas = $query->latest()->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/Plantillas/Index', [
            'plantillas' => $plantillas,
            'filters' => [
                'search' => $search,
                'perPage' => $perPage,
            ],
            'success' => session('success'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Plantillas/Create', [
            'equipos' => Equipo::select('id', 'nombre')->get(),
            'formaciones' => Formacion::select('id', 'nombre')->get(),
            'jugadores' => User::where('role', 'jugador')->select('id', 'name')->get(),
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

        Plantilla::create($request->only([
            'id_equipo',
            'id_jugador',
            'posicion',
            'numero',
        ]));

        Session::flash('success', 'Plantilla creada correctamente.');

        return redirect()->route('admin.plantillas.index');
    }

    public function edit(Plantilla $plantilla)
    {
        return Inertia::render('Admin/Plantillas/Edit', [
            'plantilla' => $plantilla->load(['equipo', 'jugador']),
            'equipos' => Equipo::select('id', 'nombre')->get(),
            'jugadores' => User::where('role', 'jugador')->select('id', 'name')->get(),
            'success' => session('success'),
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
        $plantillas = Plantilla::onlyTrashed()->latest()->paginate(10);

        return Inertia::render('Admin/Plantillas/Trashed', [
            'plantillas' => $plantillas,
            'success' => session('success'),
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
