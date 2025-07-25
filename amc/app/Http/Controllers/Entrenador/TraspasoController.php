<?php
namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use App\Models\Traspaso;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraspasoController extends Controller
{
    // Listar solicitudes creadas por el entrenador
    public function index()
    {
        $user = Auth::user();

        // Obtener equipos que controla el entrenador
        $equipos = Equipo::where('id_usuario', $user->id)->pluck('id')->toArray();

        $traspasos = Traspaso::with(['jugador', 'equipoOrigen', 'equipoDestino'])
            ->whereIn('id_equipo_destino', $equipos)
            ->get();

        return inertia('Entrenador/Traspasos/Index', compact('traspasos'));
    }

    // Mostrar formulario creación
    public function create()
    {
        $user = Auth::user();

        $equipos = Equipo::where('id_usuario', $user->id)->get();

        $jugadores = User::where('role', 'jugador')
            ->with(['plantilla.equipo']) // ya trae datos desde Plantilla
            ->get(['id', 'name', 'foto'])
            ->map(function ($jugador) {
                $plantilla = $jugador->plantilla;

                return [
                    'id' => $jugador->id,
                    'name' => $jugador->name,
                    'foto' => $jugador->foto,
                    'equipo_actual' => $plantilla && $plantilla->equipo
                        ? [
                            'id' => $plantilla->equipo->id,
                            'nombre' => $plantilla->equipo->nombre,
                        ]
                        : null,
                    'rol' => $plantilla?->rol,
                    'posicion' => $plantilla?->posicion,
                    'numero' => $plantilla?->numero,
                ];
            });

        return inertia('Entrenador/Traspasos/Create', [
            'equipos' => $equipos,
            'jugadores' => $jugadores,
        ]);
    }



    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'id_jugador' => 'required|exists:users,id',
            'id_origen' => 'nullable|exists:equipos,id',
            'id_destino' => 'required|exists:equipos,id',
            'motivo' => 'nullable|string',
        ]);

        // Validar que el equipo destino pertenece al entrenador
        $equipoDestino = Equipo::where('id', $validated['id_destino'])
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        Traspaso::create([
            'id_jugador' => $validated['id_jugador'],
            'id_equipo_origen' => $validated['id_origen'] ?? null,
            'id_equipo_destino' => $equipoDestino->id,
            'motivo' => $validated['motivo'] ?? null,
            'estado' => 'pendiente',
            'fecha_traspaso' => now(),
        ]);

        return redirect()->route('entrenador.traspasos.index')
            ->with('success', 'Solicitud de traspaso creada correctamente.');
    }

}
