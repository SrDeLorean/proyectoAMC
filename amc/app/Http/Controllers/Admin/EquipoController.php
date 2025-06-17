<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use App\Models\Formacion;
use App\Models\User;


class EquipoController extends Controller
{
    private const DEFAULT_LOGO = 'images/equipos/default-equipo.png';

    public function index()
    {
        $equipos = Equipo::with(['formacion', 'propietario', 'entrenador']) // relaciones necesarias
            ->paginate(10) // <--- paginación real
            ->through(function ($equipo) {
                $equipo->logo_url = $equipo->logo
                    ? asset($equipo->logo)
                    : asset(self::DEFAULT_LOGO);
                return $equipo;
            });

        return Inertia::render('Admin/Equipos/Index', [
            'equipos' => $equipos,
            'success' => session('success'),
        ]);
    }

    public function create()
    {
        $entrenadores = User::where('role', 'entrenador')->select('id', 'name')->get();
        $formaciones = Formacion::select('id', 'nombre')->get();
        return Inertia::render('Admin/Equipos/Create', [
            'usuarios' => $entrenadores,
            'formaciones' => $formaciones,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'descripcion'      => 'nullable|string',
            'color_primario'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'color_secundario' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'logo'             => 'nullable|image|max:2048',
            'id_formacion'     => 'nullable|integer|exists:formaciones,id',
            'instagram'        => 'nullable|string|max:255',
            'twitch'           => 'nullable|string|max:255',
            'youtube'          => 'nullable|string|max:255',
            'id_usuario'       => 'nullable|integer|exists:users,id',
            'id_usuario2'      => 'nullable|integer|exists:users,id',  // <--- Agregado aquí
        ]);

        if ($request->hasFile('logo')) {
            $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
            $path = 'images/equipos/' . $filename;
            $request->file('logo')->move(public_path('images/equipos'), $filename);
            $validated['logo'] = $path;
        } else {
            $validated['logo'] = self::DEFAULT_LOGO;
        }

        Equipo::create($validated);

        return redirect()->route('equipos.index')->with('success', 'Equipo creado correctamente.');
    }

    public function edit(Equipo $equipo)
    {
        $equipoData = $equipo->toArray();
        $equipoData['logo_url'] = $equipo->logo
            ? asset($equipo->logo)
            : asset(self::DEFAULT_LOGO);

        // Traer usuarios para selects también para editar (falta en tu método original)
        $entrenadores = User::where('role', 'entrenador')->select('id', 'name')->get();
        $formaciones = Formacion::select('id', 'nombre')->get();

        return Inertia::render('Admin/Equipos/Edit', [
            'equipo' => $equipoData,
            'usuarios' => $entrenadores,      // <-- agregado para select usuario
            'formaciones' => $formaciones,    // <-- agregado para select formacion
        ]);
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'descripcion'      => 'nullable|string',
            'color_primario'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'color_secundario' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'logo'             => 'nullable|image|max:2048',
            'id_formacion'     => 'nullable|integer|exists:formaciones,id',
            'instagram'        => 'nullable|string|max:255',
            'twitch'           => 'nullable|string|max:255',
            'youtube'          => 'nullable|string|max:255',
            'id_usuario'       => 'nullable|integer|exists:users,id',
            'id_usuario2'      => 'nullable|integer|exists:users,id',  // <--- Agregado aquí
        ]);

        if ($request->hasFile('logo')) {
            // Borrar el logo anterior si no es el default
            if ($equipo->logo && $equipo->logo !== self::DEFAULT_LOGO && File::exists(public_path($equipo->logo))) {
                File::delete(public_path($equipo->logo));
            }

            $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
            $path = 'images/equipos/' . $filename;
            $request->file('logo')->move(public_path('images/equipos'), $filename);
            $validated['logo'] = $path;
        }

        $equipo->update($validated);

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente.');
    }

    public function trashed()
    {
        $equipos = Equipo::onlyTrashed()->get();

        return Inertia::render('Admin/Equipos/Trashed', [
            'equipos' => $equipos,
            'success' => session('success'),
        ]);
    }

    public function restore($id)
    {
        $equipo = Equipo::onlyTrashed()->findOrFail($id);
        $equipo->restore();

        return redirect()->route('equipos.trashed')->with('success', 'Equipo restaurado correctamente');
    }
}
