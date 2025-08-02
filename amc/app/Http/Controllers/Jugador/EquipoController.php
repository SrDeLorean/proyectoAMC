<?php

namespace App\Http\Controllers\Jugador;

use App\Http\Controllers\Controller;

use App\Models\Equipo;
use App\Models\Plantilla;
use App\Models\Temporada;
use App\Models\Competencia;
use App\Models\TemporadaCompetencia;
use App\Models\EstadisticaEquipo;
use App\Models\Calendario;
use App\Models\TemporadaPlantilla;

use Inertia\Inertia;

class EquipoController extends Controller
{
    /**
     * Este metodo va a mostrar el equipo del usuario logueado
     * la plantilla del equipo
     * que muestre la temporada actual
     * calendario de la temporada
     * y las estadisticas del equipo
     * y las estadisticas de los jugadores
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = auth()->user();

        // Obtener el primer equipo del usuario (si tiene varios)
        $equipo = $user->equipos()->first();

        // Si no tiene equipo, retornar una respuesta adecuada
        if (!$equipo) {
            return Inertia::render('Jugador/Equipos/Index', [
                'jugador' => $user,
                'equipo' => null,
                'plantilla' => [],
                'mensaje' => 'Aún no estás asignado a ningún equipo.',
            ]);
        }

        // Obtener la plantilla asociada al equipo
        $plantilla = Plantilla::with('jugador')
                            ->where('equipo_id', $equipo->id)
                            ->get();

        return Inertia::render('Jugador/Equipos/Index', [
            'jugador' => $user,
            'equipo' => $equipo,
            'plantilla' => $plantilla,
        ]);
    }


}
