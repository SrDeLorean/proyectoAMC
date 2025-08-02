<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Traspaso;
use App\Models\TemporadaPlantilla;
use App\Models\TemporadaEquipo;
use Illuminate\Http\Request;

class TraspasoController extends Controller
{
    // Mostrar todos los traspasos para admin
    public function index()
    {
        $traspasos = Traspaso::with(['jugador', 'equipoOrigen', 'equipoDestino'])
            ->orderBy('fecha_traspaso', 'desc')
            ->get();

        // dd($traspasos); // comentar o borrar para pasar datos a la vista

        return inertia('Admin/Traspasos/Index', compact('traspasos'));
    }

    // Ver detalle (opcional)
    public function show($id)
    {
        $traspaso = Traspaso::with(['jugador', 'equipoOrigen', 'equipoDestino'])->findOrFail($id);
        return inertia('Admin/Traspasos/Show', compact('traspaso'));
    }

    // Aprobar traspaso (solo admin)
    public function aprobar($id)
    {
        $traspaso = Traspaso::findOrFail($id);

        if ($traspaso->estado !== 'aceptado') {
            return back()->withErrors('El traspaso debe estar aceptado por el jugador para aprobarlo.');
        }

        // Cambiar estado a aprobado
        $traspaso->update([
            'estado' => 'aprobado',
            'fecha_revision_admin' => now(),
        ]);

        $idJugador = $traspaso->id_jugador;
        $idOrigen = $traspaso->id_equipo_origen;
        $idDestino = $traspaso->id_equipo_destino;

        // 1. Cerrar TODAS las plantillas activas del jugador en el equipo ORIGEN
        $plantillasOrigen = TemporadaPlantilla::where('id_jugador', $idJugador)
            ->whereNull('fecha_salida')
            ->whereHas('temporadaEquipo', function ($query) use ($idOrigen) {
                $query->where('id_equipo', $idOrigen);
            })
            ->get();

        $rol = 'Jugador';
        $posicion = 'Por definir';
        $numero = 0;

        foreach ($plantillasOrigen as $plantilla) {
            $plantilla->update(['fecha_salida' => now()]);
            $rol = $plantilla->rol ?? $rol;
            $posicion = $plantilla->posicion ?? $posicion;
            $numero = $plantilla->numero ?? $numero;
        }

        // 2. Verificar si el jugador tiene más registros activos en TemporadaPlantilla del equipo origen
        $aunActivoEnOrigen = TemporadaPlantilla::where('id_jugador', $idJugador)
            ->whereNull('fecha_salida')
            ->whereHas('temporadaEquipo', function ($query) use ($idOrigen) {
                $query->where('id_equipo', $idOrigen);
            })
            ->exists();

        // Si ya no tiene presencia activa en el equipo origen, eliminar de PLANTILLA
        if (!$aunActivoEnOrigen) {
            \App\Models\Plantilla::where('id_jugador', $idJugador)
                ->where('id_equipo', $idOrigen)
                ->delete(); // Usa SoftDelete
        }

        // 3. Crear o actualizar en PLANTILLA del equipo DESTINO
        \App\Models\Plantilla::updateOrCreate(
            [
                'id_equipo' => $idDestino,
                'id_jugador' => $idJugador,
            ],
            [
                'rol' => $rol,
                'posicion' => $posicion,
                'numero' => $numero,
            ]
        );

        // 4. Obtener TODAS las competencias activas del equipo destino
        $temporadasDestino = TemporadaEquipo::where('id_equipo', $idDestino)
            ->where('activo', true)
            ->get();

        if ($temporadasDestino->isEmpty()) {
            return back()->withErrors('No se encontró temporada activa para el equipo destino.');
        }

        // 5. Agregar a TemporadaPlantilla del equipo destino (todas las activas)
        foreach ($temporadasDestino as $temporadaEquipo) {
            $yaExiste = TemporadaPlantilla::where('id_jugador', $idJugador)
                ->where('id_temporada_equipo', $temporadaEquipo->id)
                ->exists();

            if (!$yaExiste) {
                TemporadaPlantilla::create([
                    'id_jugador' => $idJugador,
                    'id_temporada_equipo' => $temporadaEquipo->id,
                    'rol' => $rol,
                    'posicion' => $posicion,
                    'numero' => $numero,
                    'fecha_ingreso' => now(),
                ]);
            }
        }

        return back()->with('success', 'Traspaso aprobado correctamente.');
    }


    // Cancelar traspaso (admin)
    public function cancelar($id)
    {
        $traspaso = Traspaso::findOrFail($id);

        $traspaso->update([
            'estado' => 'cancelado',
            'fecha_revision_admin' => now(),
        ]);

        return back()->with('success', 'Traspaso cancelado.');
    }

    protected function obtenerTemporadaEquipoActual($id_equipo)
    {
        $temporadaEquipo = TemporadaEquipo::where('id_equipo', $id_equipo)
            ->where('activo', true)
            ->first();

        return $temporadaEquipo ? $temporadaEquipo->id : null;
    }
}
