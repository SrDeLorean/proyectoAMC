<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Inertia\Inertia;
use Inertia\Response;

class EquipoController extends Controller
{
    public function show(int $id): Response
    {
        $equipo = Equipo::findOrFail($id);

        // Aquí puedes cargar relaciones si las necesitas, ejemplo plantillas, etc.

        return Inertia::render('Guest/Equipo/Show', [
            'equipo' => $equipo,
        ]);
    }
}
