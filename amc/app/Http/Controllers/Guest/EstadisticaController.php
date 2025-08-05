<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\EstadisticaJugador;

class EstadisticaController extends Controller
{


    public function index()
    {
        $estadisticas = [
            'calificacion',
            'goles',
            'asistencias',
            'tiros',
            'precision_tiros',
            'pases',
            'precision_pases',
            'regates',
            'tasa_exito_entradas',
            'fueras_de_juego',
            'faltas_cometidas',
            'posesion_ganada',
            'posesion_perdida',
            'minutos_jugados_vs_equipo',
            'distancia_total_vs_equipo',
            'distancia_carrera_vs_equipo',
        ];

        $resultados = [];

        foreach ($estadisticas as $metrica) {
           $top3 = EstadisticaJugador::with(['jugador', 'equipo'])
                ->orderByDesc($metrica)
                ->take(3)
                ->get()
                ->map(function ($est, $index) use ($metrica) {
                    return [
                        'id_jugador' => $est->id_jugador,
                        'nombre' => $est->jugador->id_ea ?? 'N/A',

                        // Aquí el cambio clave para la foto del jugador:
                        'playerImage' =>$est->jugador->foto,

                        'club' => $est->equipo->nombre ?? 'Libre',

                        // Igual que el jugador, para el logo del club:
                        'clubLogo' => $est->equipo->logo,

                        'clubColor' => $est->equipo->color_primario ?? '#333333',
                        'statValue' => $est->$metrica ?? 0,
                        'rank' => $index + 1,
                    ];
                });

            $clubLogo = $top3->first()['clubLogo'] ?? asset('images/club-default.png');
            $clubColor = $top3->first()['clubColor'] ?? '#333333';

            $resultados[$metrica] = [
                'title' => ucfirst(str_replace('_', ' ', $metrica)),
                'clubLogo' => $clubLogo,
                'clubColor' => $clubColor,
                'players' => $top3->toArray(),
            ];
        }

        return Inertia::render('Guest/Estadisticas/Index', [
            'topEstadisticas' => $resultados,
        ]);
    }
}
