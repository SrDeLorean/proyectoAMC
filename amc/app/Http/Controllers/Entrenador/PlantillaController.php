<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantillaController extends Controller
{
    // Mostrar plantillas (jugadores) de los equipos que controla el entrenador
    public function index()
    {
        $user = Auth::user();

        // Equipos que maneja el entrenador
        $equiposIds = Equipo::where('id_usuario', $user->id)->pluck('id');

        // Plantillas activas (sin softdelete) de esos equipos
        $plantillas = Plantilla::with(['jugador', 'equipo'])
            ->whereIn('id_equipo', $equiposIds)
            ->get();

        return inertia('Entrenador/Plantillas/Index', compact('plantillas'));
    }

    // Mostrar formulario para editar un jugador en plantilla
    public function edit($id)
    {
        $plantilla = Plantilla::with(['jugador', 'equipo'])->findOrFail($id);

        $user = Auth::user();
        if ($plantilla->equipo->id_usuario !== $user->id) {
            abort(403);
        }

        return inertia('Entrenador/Plantillas/Edit', [
            'plantilla' => $plantilla,
        ]);
    }

    // Actualizar rol, posición, número
    public function update(Request $request, $id)
    {
        $plantilla = Plantilla::with('equipo')->findOrFail($id);

        $user = Auth::user();

        if ($plantilla->equipo->id_usuario !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'rol' => 'required|string|max:255',
            'posicion' => 'required|string|max:255',
            'numero' => 'required|integer|min:1',
        ]);

        $plantilla->update($validated);

        return redirect()->route('entrenador.plantillas.index')
            ->with('success', 'Plantilla actualizada correctamente.');
    }
}
