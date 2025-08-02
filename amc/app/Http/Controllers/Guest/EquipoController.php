<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Plantilla;

class EquipoController extends Controller
{

    /**
     * Muestra la lista de equipos.
     *
     * @return \Inertia\Response
     */

    public function index(): Response
    {
        $equipos = Equipo::with(['propietario', 'entrenador'])->get();

        return Inertia::render('Guest/Equipos/Index', [
            'equipos' => $equipos,
        ]);
    }

    /**
     * Muestra los detalles de un equipo específico.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $equipo = Equipo::with('formacion', 'propietario', 'entrenador')->findOrFail($id);

        $plantillaRaw = Plantilla::with('jugador')
            ->where('id_equipo', $equipo->id)
            ->get();

        $plantilla = $plantillaRaw->map(function ($item) {
            return [
                'rol' => $item->rol,
                'posicion' => $item->posicion,
                'numero' => $item->numero,
                'titular' => $item->titular,
                'jugador' => [
                    'id' => $item->jugador->id ?? null,
                    'name' => $item->jugador->name ?? '',
                    'foto' => $item->jugador->foto
                        ? asset($item->jugador->foto)
                        : asset('images/users/default-user.png'),
                    'nacionalidad' => $item->jugador->nacionalidad ?? 'Desconocido',
                    'id_ea' => $item->jugador->id_ea ?? 'N/A',
                ],
            ];
        });

        return Inertia::render('Guest/Equipos/Show', [
            'equipo' => $equipo,
            'plantilla' => $plantilla,
        ]);
    }
}
