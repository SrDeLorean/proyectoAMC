<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EquipoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscar el primer equipo donde el usuario es propietario o entrenador
        $equipo = Equipo::with('formacion')
                    ->where(function ($query) use ($user) {
                        $query->where('id_usuario', $user->id)
                              ->orWhere('id_usuario2', $user->id);
                    })
                    ->first();

        if (!$equipo) {
            // Si no tiene equipo, vista vacía
            return Inertia::render('Entrenador/Equipos/Index', [
                'equipo' => null,
                'plantilla' => [],
                'jugador' => $user,
            ]);
        }

        // Obtener la plantilla con el jugador relacionado
        $plantilla = Plantilla::with('jugador')
                        ->where('id_equipo', $equipo->id)
                        ->get();

        return Inertia::render('Entrenador/Equipos/Index', [
            'equipo' => $equipo,
            'plantilla' => $plantilla,
            'jugador' => $user,
        ]);
    }
}
