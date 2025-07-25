<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class EstadisticasJugadorController extends Controller
{
    public function mostrarPorTipo(string $tipo): Response
    {
        $data = [
            'goles' => [
    'titulo' => 'Goles',
    'color' => '#ff003c',
    'jugadores' => [
        ['rank' => 1, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 30, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 2, 'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 27, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
        ['rank' => 3, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 25, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 4, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 22, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 5, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 20, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 6, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 18, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 7, 'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 15, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
        ['rank' => 8, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 14, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 9, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 12, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 10,'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 10, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
    ],
],
'asistencias' => [
    'titulo' => 'Asistencias',
    'color' => '#0099ff',
    'jugadores' => [
        ['rank' => 1, 'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 30, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
        ['rank' => 2, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 27, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 3, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 25, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 4, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 22, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 5, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 20, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 6, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 18, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 7, 'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 15, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
        ['rank' => 8, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 14, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 9, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 12, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 10,'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 10, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
    ],
],
'pases' => [
    'titulo' => 'Pases',
    'color' => '#00cc66',
    'jugadores' => [
        ['rank' => 1, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 1500, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 2, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 1200, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 3, 'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 1150, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
        ['rank' => 4, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 1100, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 5, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 1080, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 6, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 1020, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 7, 'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 1000, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
        ['rank' => 8, 'firstName' => 'Robert', 'lastName' => 'Lewandowski', 'playerImage' => '/images/jugadores/lewandowski.png', 'playerName' => 'Robert Lewandowski', 'statValue' => 980, 'clubLogo' => '/images/equipos/barcelona.png', 'clubName' => 'FC Barcelona'],
        ['rank' => 9, 'firstName' => 'Kylian', 'lastName' => 'Mbappé', 'playerImage' => '/images/jugadores/mbappe.png', 'playerName' => 'Kylian Mbappé', 'statValue' => 950, 'clubLogo' => '/images/equipos/realmadrid.png', 'clubName' => 'Real Madrid'],
        ['rank' => 10,'firstName' => 'Erling', 'lastName' => 'Haaland', 'playerImage' => '/images/jugadores/haaland.png', 'playerName' => 'Erling Haaland', 'statValue' => 900, 'clubLogo' => '/images/equipos/manchestercity.png', 'clubName' => 'Manchester City'],
    ],
],
        ];

        $estadistica = $data[$tipo] ?? abort(404);

        return Inertia::render('Ligas/EstadisticasTableView', [
            'titulo' => $estadistica['titulo'],
            'backgroundColor' => $estadistica['color'],
            'jugadores' => $estadistica['jugadores'],
        ]);
    }
}
