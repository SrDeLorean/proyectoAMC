<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PlantillaController extends Controller
{
    // Mostrar plantillas (jugadores) de los equipos que controla el entrenador
    public function index()
    {
        $user = Auth::user();

        // Equipos que maneja el entrenador o el dueño
        $equiposIds = Equipo::where(function ($query) use ($user) {
            $query->where('id_usuario', $user->id)
                  ->orWhere('id_usuario2', $user->id);
        })->pluck('id');

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

        $equipo = $plantilla->equipo;

        // Permitir acceso si es dueño (id_usuario) o entrenador (id_usuario2)
        if ($equipo->id_usuario !== $user->id && $equipo->id_usuario2 !== $user->id) {
            abort(403);
        }

        return inertia('Entrenador/Plantillas/Edit', [
            'plantilla' => $plantilla,
        ]);
    }

    // Actualizar rol, posición, número
    public function update(Request $request, $id)
    {
        $plantilla = Plantilla::findOrFail($id);

        $user = Auth::user();
        $equipo = $plantilla->equipo;

        if ($equipo->id_usuario !== $user->id && $equipo->id_usuario2 !== $user->id) {
            abort(403);
        }

        $request->validate([
            'rol' => 'required|string',
            'posicion' => 'required|string',
            'numero' => 'required|integer',
            'titular' => 'boolean',
        ]);

        $plantilla->update([
            'rol' => $request->rol,
            'posicion' => $request->posicion,
            'numero' => $request->numero,
            'titular' => $request->titular ?? false,
        ]);

        return redirect()
            ->route('entrenador.plantillas.edit', $plantilla->id)
            ->with('success', 'Jugador actualizado correctamente');
    }

    public function destroy($id)
    {
        $plantilla = Plantilla::with('equipo')->findOrFail($id);

        $user = Auth::user();
        $equipo = $plantilla->equipo;

        // Verificación de autorización: solo dueño o entrenador
        if ($equipo->id_usuario !== $user->id && $equipo->id_usuario2 !== $user->id) {
            abort(403);
        }

        // Soft delete
        $plantilla->delete();

        return redirect()
            ->route('entrenador.plantillas.index')
            ->with('success', 'Jugador liberado correctamente.');
    }


}
