<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    private const REPORTES_PATH = 'images/reportes';

    public function create($calendarioId)
    {
        return inertia('Entrenador/Reportes/Create', [
            'calendarioId' => (int) $calendarioId,
        ]);
    }

    public function store(Request $request, $calendarioId)
    {
        $validated = $request->validate([
            'foto_rendimiento_partido' => 'required|image|max:5120',
            'foto_listado_id_partido' => 'required|image|max:5120',
            'foto_rendimientos_individuales' => 'required|image|max:5120',
        ]);

        $calendario = Calendario::findOrFail($calendarioId);
        $user = Auth::user();

        // Obtener equipo donde el usuario es propietario o entrenador
        $equipo = \App\Models\Equipo::where(function ($query) use ($user) {
            $query->where('id_usuario', $user->id)
                  ->orWhere('id_usuario2', $user->id);
        })->where(function($query) use ($calendario) {
            // Que sea local o visitante en el partido
            $query->where('id', $calendario->id_equipo_local)
                  ->orWhere('id', $calendario->id_equipo_visitante);
        })->first();

        if (!$equipo) {
            abort(403, 'No tienes asignado ningún equipo como propietario o entrenador para este partido.');
        }

        // Determinar si es local o visitante
        if ($equipo->id === $calendario->id_equipo_local) {
            $equipoTipo = 'local';
        } elseif ($equipo->id === $calendario->id_equipo_visitante) {
            $equipoTipo = 'visitante';
        } else {
            abort(403, 'No autorizado para subir reporte para este partido.');
        }

        $camposImagenBase = [
            'foto_rendimiento_partido' => 'foto_rendimiento',
            'foto_listado_id_partido' => 'foto_lista_id',
            'foto_rendimientos_individuales' => 'foto_rendimiento_jugadores',
        ];

        foreach ($camposImagenBase as $inputName => $campoBase) {
            $campoDB = $campoBase . '_' . $equipoTipo;

            if ($request->hasFile($inputName)) {
                $rutaVieja = $calendario->$campoDB;
                if ($rutaVieja && file_exists(public_path($rutaVieja))) {
                    unlink(public_path($rutaVieja));
                }

                $file = $request->file($inputName);
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path(self::REPORTES_PATH), $filename);

                $calendario->$campoDB = self::REPORTES_PATH . '/' . $filename;
            }
        }

        $calendario->save();

        Session::flash('success', 'Reporte guardado correctamente.');

        return redirect()->route('entrenador.reportes.create', $calendarioId);
    }
}
