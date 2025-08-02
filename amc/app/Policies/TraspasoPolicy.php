<?php

namespace App\Policies;

use App\Models\Traspaso;
use App\Models\User;
use App\Models\Equipo;

class TraspasoPolicy
{
    public function crear(User $user, Equipo $equipoDestino)
    {
        return $user->role === 'administrador' ||
            ($user->role === 'entrenador' && $equipoDestino->id_usuario == $user->id);
    }

    public function aceptar(User $user, Traspaso $traspaso)
    {
        return $user->id === $traspaso->id_jugador && $traspaso->estado === 'pendiente';
    }

    public function aprobar(User $user, Traspaso $traspaso)
    {
        return $user->role === 'administrador' && $traspaso->estado === 'aceptado';
    }

    public function ver(User $user, Traspaso $traspaso)
    {
        // Admin ve todo
        if ($user->role === 'administrador') {
            return true;
        }
        // Entrenador solo fichajes para sus equipos
        if ($user->role === 'entrenador') {
            return $traspaso->equipoDestino && $traspaso->equipoDestino->id_usuario == $user->id;
        }
        // Jugador solo sus fichajes
        if ($user->role === 'jugador') {
            return $traspaso->id_jugador == $user->id;
        }
        return false;
    }
}
