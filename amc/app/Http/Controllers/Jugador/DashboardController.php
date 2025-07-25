<?php

namespace App\Http\Controllers\Jugador;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

class DashboardController extends Controller{

    /**
     * Este metodo va a mostrar los datos de usuario como nombre, email, etc.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Jugador/Dashboard', [
            'user' => auth()->user()
        ]);
    }
}
