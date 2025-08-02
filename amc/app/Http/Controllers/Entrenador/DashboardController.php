<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\EstadisticaJugador;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\Equipo;
use App\Models\Plantilla;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Muestra los datos del entrenador y resumen de estadísticas de sus jugadores.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Buscar el primer equipo donde el usuario es propietario o entrenador
        $equipo = Equipo::with(['formacion', 'propietario', 'entrenador'])
            ->where(function ($query) use ($user) {
                $query->where('id_usuario', $user->id)
                      ->orWhere('id_usuario2', $user->id);
            })
            ->first();

        if (!$equipo) {
            return Inertia::render('Entrenador/Dashboard/Index', [
                'equipo' => null,
                'plantilla' => [],
                'user' => $user,
                'estadisticas' => [],
            ]);
        }

        // Obtener la plantilla con el jugador relacionado
        $plantilla = Plantilla::with('jugador')
                        ->where('id_equipo', $equipo->id)
                        ->get();

        // Formatear URLs de redes sociales para evitar enviar links inválidos
        $equipo->instagram = $equipo->instagram ? 'https://instagram.com/' . ltrim($equipo->instagram, '@') : null;
        $equipo->twitch = $equipo->twitch ? 'https://twitch.tv/' . ltrim($equipo->twitch, '@') : null;
        $equipo->youtube = $equipo->youtube ? 'https://youtube.com/' . ltrim($equipo->youtube, '@') : null;

        // Aquí deberías traer estadísticas, aquí pongo un array vacío para ejemplo
        $estadisticas = [];

        return Inertia::render('Entrenador/Dashboard/Index', [
            'equipo' => $equipo,
            'plantilla' => $plantilla,
            'user' => $user,
            'estadisticas' => $estadisticas,
        ]);
    }

    public function show(User $user)
    {
        $plantilla = $user->plantillaActual()->with('equipo')->first();

        if (!$plantilla) {
            $plantilla = $user->plantilla()->with('equipo')->first();
        }

        $estadisticas = EstadisticaJugador::with(['temporadaCompetencia.competencia'])
            ->where('id_jugador', $user->id)
            ->get();

        // Convierte logo a url absoluta con asset()
        $equipo = $plantilla ? $plantilla->equipo : null;
        if ($equipo && $equipo->logo && !str_starts_with($equipo->logo, 'http')) {
            $equipo->logo = asset($equipo->logo);
        }

        return Inertia::render('Entrenador/Dashboard/Show', [
            'user' => $user->append('profile_photo_url')->makeHidden(['password', 'remember_token']),
            'plantilla' => $plantilla,
            'equipo' => $equipo,
            'estadisticas' => $estadisticas,
        ]);
    }
}
