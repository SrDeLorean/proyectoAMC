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

        // Buscar el primer equipo donde es entrenador
        $equipo = $user->equiposComoEntrenador()->with('formacion')->first();

        if (!$equipo) {
            // Si no tiene equipo, redireccionamos o mostramos vista vacía
            return Inertia::render('Entrenador/Equipos/Index', [
                'equipo' => null,
                'plantilla' => [],
                'jugador' => $user,
            ]);
        }

        // Obtener plantilla del equipo con datos del jugador relacionados
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
