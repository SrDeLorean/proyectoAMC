<?php
namespace App\Http\Traits;

trait RedirectsUsersByRole
{
    protected function redirectToByRole()
    {
        $user = auth()->user();

        return match ($user->role) {
            'administrador' => route('admin.dashboard'),
            'entrenador'    => route('entrenador.dashboard'),
            'jugador'       => route('jugador.dashboard'),
            default         => route('dashboard'),
        };
    }
}
