<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

use Inertia\Inertia;
use App\Models\User;

class JugadorController extends Controller
{

    public function index()
    {
        $jugadores = User::with(['plantilla.equipo'])
            ->where('role', 'jugador')
            ->orderBy('name')
            ->get();

        return Inertia::render('Guest/Jugadores/Index', [
            'jugadores' => $jugadores,
        ]);
    }

    /**
     * Muestra el detalle de un jugador.
     */
    public function show($id)
    {
        $jugador = User::with(['plantilla.equipo'])->findOrFail($id);

        return Inertia::render('Guest/Jugadores/Show', [
            'jugador' => [
                'id'            => $jugador->id,
                'name'          => $jugador->name,
                'foto'          => $jugador->profile_photo_url,
                'posicion'      => $jugador->posicion,
                'nacionalidad'  => $jugador->nacionalidad,
                'altura'        => $jugador->altura,
                'peso'          => $jugador->peso,
                'instagram'     => $jugador->instagram,
                'twitch'        => $jugador->twitch,
                'youtube'       => $jugador->youtube,
                'equipo'        => $jugador->plantilla?->equipo?->nombre,
                'equipo_id'     => $jugador->plantilla?->equipo?->id,
            ]
        ]);
    }
}
