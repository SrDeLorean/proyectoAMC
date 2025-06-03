<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;  // <--- esta línea es clave
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $golesPlayers = [
            [
                'rank' => 1,
                'firstName' => 'Kylian',
                'lastName' => 'Mbappé',
                'playerImage' => '/images/jugadores/mbappe.png',
                'playerName' => 'Kylian Mbappé',
                'statValue' => 28,
                'clubLogo' => '/images/equipos/realmadrid.png',
            ],
            [
                'rank' => 2,
                'firstName' => 'Erling',
                'lastName' => 'Haaland',
                'playerImage' => '/images/jugadores/haaland.png',
                'playerName' => 'Erling Haaland',
                'statValue' => 22,
                'clubLogo' => '/images/equipos/manchestercity.png',
            ],
            [
                'rank' => 3,
                'firstName' => 'Robert',
                'lastName' => 'Lewandowski',
                'playerImage' => '/images/jugadores/lewandowski.png',
                'playerName' => 'Robert Lewandowski',
                'statValue' => 20,
                'clubLogo' => '/images/equipos/barcelona.png',
            ],
        ];

        $asistenciasPlayers = [
            [
                'rank' => 1,
                'firstName' => 'Erling',
                'lastName' => 'Haaland',
                'playerImage' => '/images/jugadores/haaland.png',
                'playerName' => 'Erling Haaland',
                'statValue' => 40,
                'clubLogo' => '/images/equipos/manchestercity.png',
            ],
            [
                'rank' => 2,
                'firstName' => 'Kylian',
                'lastName' => 'Mbappé',
                'playerImage' => '/images/jugadores/mbappe.png',
                'playerName' => 'Kylian Mbappé',
                'statValue' => 30,
                'clubLogo' => '/images/equipos/realmadrid.png',
            ],
            [
                'rank' => 3,
                'firstName' => 'Robert',
                'lastName' => 'Lewandowski',
                'playerImage' => '/images/jugadores/lewandowski.png',
                'playerName' => 'Robert Lewandowski',
                'statValue' => 20,
                'clubLogo' => '/images/equipos/barcelona.png',
            ],
        ];

        $pasesPlayers = [
            [
                'rank' => 1,
                'firstName' => 'Robert',
                'lastName' => 'Lewandowski',
                'playerImage' => '/images/jugadores/lewandowski.png',
                'playerName' => 'Robert Lewandowski',
                'statValue' => 1500,
                'clubLogo' => '/images/equipos/barcelona.png',
            ],
            [
                'rank' => 2,
                'firstName' => 'Erling',
                'lastName' => 'Haaland',
                'playerImage' => '/images/jugadores/haaland.png',
                'playerName' => 'Erling Haaland',
                'statValue' => 1256,
                'clubLogo' => '/images/equipos/manchestercity.png',
            ],
            [
                'rank' => 3,
                'firstName' => 'Kylian',
                'lastName' => 'Mbappé',
                'playerImage' => '/images/jugadores/mbappe.png',
                'playerName' => 'Kylian Mbappé',
                'statValue' => 1100,
                'clubLogo' => '/images/equipos/realmadrid.png',
            ],
        ];

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'golesPlayers' => $golesPlayers,
            'asistenciasPlayers' => $asistenciasPlayers,
            'pasesPlayers' => $pasesPlayers,
        ]);
    }
}
