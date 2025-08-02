<?php

namespace App\Http\Controllers\Entrenador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

use App\Models\Equipo;
use App\Models\Formacion;
use App\Models\Plantilla;
use App\Models\User;

class EquipoController extends Controller
{
    private const DEFAULT_LOGO = 'images/equipos/default-equipo.png';

    public function index()
    {
        $user = Auth::user();

        // Aquí pueden ver el equipo propietario o entrenador
        $equipo = Equipo::with('formacion')
            ->where(function ($query) use ($user) {
                $query->where('id_usuario', $user->id)
                      ->orWhere('id_usuario2', $user->id);
            })
            ->first();

        if (!$equipo) {
            return Inertia::render('Entrenador/Equipos/Index', [
                'equipo' => null,
                'plantilla' => [],
                'jugador' => $user,
            ]);
        }

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

        return Inertia::render('Entrenador/Equipos/Index', [
            'equipo' => $equipo,
            'plantilla' => $plantilla,
            'jugador' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();

        // SOLO el dueño puede editar: filtro por id_usuario
        $equipo = Equipo::where('id_usuario', $user->id)->firstOrFail();

        $usuarios = User::whereHas('plantilla', function ($query) use ($equipo) {
            $query->where('id_equipo', $equipo->id);
        })->select('id', 'name')->get();

        $formaciones = Formacion::select('id', 'nombre')->get();

        $equipoData = $equipo->toArray();
        $equipoData['logo_url'] = $equipo->logo
            ? asset($equipo->logo)
            : asset(self::DEFAULT_LOGO);

        return Inertia::render('Entrenador/Equipos/Edit', [
            'equipo' => $equipoData,
            'usuarios' => $usuarios,
            'formaciones' => $formaciones,
            'success' => session('success'),  // <-- Aquí el mensaje explícito
            'info' => session('info'),        // <-- Para el mensaje info
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // SOLO el dueño puede actualizar: filtro por id_usuario
        $equipo = Equipo::where('id', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'descripcion'      => 'nullable|string|max:1000',
            'color_primario'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'color_secundario' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'instagram'        => 'nullable|string|max:255',
            'twitch'           => 'nullable|string|max:255',
            'youtube'          => 'nullable|string|max:255',
            'id_formacion'     => 'nullable|exists:formaciones,id',
            'id_usuario2'      => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($equipo) {
                    if ($value !== null) {
                        $existe = Plantilla::where('id_equipo', $equipo->id)
                            ->where('id_jugador', $value)
                            ->exists();
                        if (!$existe) {
                            $fail("El entrenador responsable debe ser un jugador de la plantilla del equipo.");
                        }
                    }
                }
            ],
        ]);

        $datosParaActualizar = [];

        foreach (['descripcion', 'color_primario', 'color_secundario', 'id_formacion', 'instagram', 'twitch', 'youtube'] as $campo) {
            if (array_key_exists($campo, $validated)) {
                if ($equipo->{$campo} !== $validated[$campo]) {
                    $datosParaActualizar[$campo] = $validated[$campo];
                }
            }
        }

        // Manejar el cambio de entrenador responsable (id_usuario2)
        if (array_key_exists('id_usuario2', $validated)) {
            $nuevoEntrenadorId = $validated['id_usuario2'];
            $entrenadorAnteriorId = $equipo->id_usuario2;

            if ($nuevoEntrenadorId !== $entrenadorAnteriorId) {
                // Cambiar roles solo si el valor cambia

                // 1. Cambiar rol del entrenador anterior a 'jugador', si existía
                if ($entrenadorAnteriorId) {
                    $entrenadorAnterior = User::find($entrenadorAnteriorId);
                    if ($entrenadorAnterior && $entrenadorAnterior->role === 'entrenador') {
                        $entrenadorAnterior->role = 'jugador';
                        $entrenadorAnterior->save();
                    }
                }

                // 2. Cambiar rol del nuevo entrenador a 'entrenador'
                if ($nuevoEntrenadorId) {
                    $nuevoEntrenador = User::find($nuevoEntrenadorId);
                    if ($nuevoEntrenador && $nuevoEntrenador->role !== 'entrenador') {
                        $nuevoEntrenador->role = 'entrenador';
                        $nuevoEntrenador->save();
                    }
                }

                $datosParaActualizar['id_usuario2'] = $nuevoEntrenadorId;
            }
        }

        if ($request->hasFile('logo')) {
            if (
                $equipo->logo &&
                $equipo->logo !== self::DEFAULT_LOGO &&
                File::exists(public_path($equipo->logo))
            ) {
                File::delete(public_path($equipo->logo));
            }

            $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
            $path = 'images/equipos/' . $filename;
            $request->file('logo')->move(public_path('images/equipos'), $filename);
            $datosParaActualizar['logo'] = $path;
        }

        if (count($datosParaActualizar) > 0) {
            $equipo->update($datosParaActualizar);
            $mensaje = 'Equipo actualizado correctamente.';
            $tipoMensaje = 'success';
        } else {
            $mensaje = 'No hubo cambios en los datos del equipo.';
            $tipoMensaje = 'info';
        }

        return redirect()->route('entrenador.equipos.edit', $equipo->id)
            ->with([
                'success' => $tipoMensaje === 'success' ? $mensaje : null,
                'info' => $tipoMensaje === 'info' ? $mensaje : null,
            ]);
    }
}
