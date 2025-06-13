<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EquipoController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlantillaController;


use App\Http\Controllers\TemporadaController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\TemporadaCompetenciaController;
use App\Http\Controllers\TemporadaEquipoController;

use App\Http\Controllers\CalendarioController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::prefix('equipos')->name('equipos.')->group(function () {
    Route::get('/', [EquipoController::class, 'index'])->name('index');
    Route::get('/create', [EquipoController::class, 'create'])->name('create');
    Route::post('/', [EquipoController::class, 'store'])->name('store');
    Route::get('/{equipo}/edit', [EquipoController::class, 'edit'])->name('edit');
    Route::post('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');

    // Rutas para papelera
    Route::get('/trashed', [EquipoController::class, 'trashed'])->name('trashed');
    Route::post('/{id}/restore', [EquipoController::class, 'restore'])->name('restore');
});
/*
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth'])->group(function () {
    // Ruta compartida por todos
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/competencias', function () {
    return Inertia::render('Competencias');
})->name('competiciones');

Route::get('/ligas/pro-league', function () {
    return Inertia::render('Ligas/ProLeague');
})->name('ligas.proleague');

Route::get('/ligas/elite', function () {
    return Inertia::render('Ligas/Elite');
})->name('ligas.elite');

Route::get('/ligas/ascenso', function () {
    return Inertia::render('Ligas/Ascenso');
})->name('ligas.ascenso');

Route::get('/ligas/anfa', function () {
    return Inertia::render('Ligas/Anfa');
})->name('ligas.anfa');

Route::middleware(['auth'])->group(function () {

    // Dashboard raíz: redirige según rol
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'administrador' => redirect()->route('admin.dashboard'),
            'entrenador'    => redirect()->route('entrenador.dashboard'),
            'jugador'       => redirect()->route('jugador.dashboard'),
            default         => redirect()->route('dashboard'),
        };
    })->name('dashboard');

    // Dashboards con middleware de roles

    Route::middleware('role:administrador')->group(function () {
        Route::get('/admin/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('admin.dashboard');

        // Rutas de CRUD de usuarios
        Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/usuarios/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.users.store');

        Route::get('/admin/usuarios/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/admin/usuarios/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/usuarios/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/usuarios/trashed', [UserController::class, 'trashed'])->name('admin.users.trashed');
        Route::post('/admin/usuarios/{id}/restore', [UserController::class, 'restore'])->name('admin.users.restore');

        // Rutas de CRUD de plantillas
        Route::get('/admin/plantillas', [PlantillaController::class, 'index'])->name('admin.plantillas.index');
        Route::get('/admin/plantillas/create', [PlantillaController::class, 'create'])->name('admin.plantillas.create');
        Route::post('/admin/plantillas', [PlantillaController::class, 'store'])->name('admin.plantillas.store');
        Route::get('/admin/plantillas/{plantilla}/edit', [PlantillaController::class, 'edit'])->name('admin.plantillas.edit');
        Route::put('/admin/plantillas/{plantilla}', [PlantillaController::class, 'update'])->name('admin.plantillas.update');
        Route::delete('/admin/plantillas/{plantilla}', [PlantillaController::class, 'destroy'])->name('admin.plantillas.destroy');
        Route::get('/admin/plantillas/trashed', [PlantillaController::class, 'trashed'])->name('admin.plantillas.trashed');
        Route::post('/admin/plantillas/{id}/restore', [PlantillaController::class, 'restore'])->name('admin.plantillas.restore');


        // Rutas de CRUD de temporadas
        Route::get('/temporadas', [TemporadaController::class, 'index'])->name('temporadas.index');
        Route::get('/temporadas/create', [TemporadaController::class, 'create'])->name('temporadas.create');
        Route::post('/temporadas', [TemporadaController::class, 'store'])->name('temporadas.store');
        Route::get('/temporadas/{temporada}/edit', [TemporadaController::class, 'edit'])->name('temporadas.edit');
        Route::put('/temporadas/{temporada}', [TemporadaController::class, 'update'])->name('temporadas.update');
        Route::delete('/temporadas/{temporada}', [TemporadaController::class, 'destroy'])->name('temporadas.destroy');
        Route::get('/temporadas/trashed', [TemporadaController::class, 'trashed'])->name('temporadas.trashed');
        Route::post('/temporadas/{id}/restore', [TemporadaController::class, 'restore'])->name('temporadas.restore');

        Route::get('/competencias', [CompetenciaController::class, 'index'])->name('competencias.index');
        Route::get('/competencias/create', [CompetenciaController::class, 'create'])->name('competencias.create');
        Route::post('/competencias', [CompetenciaController::class, 'store'])->name('competencias.store');
        Route::get('/competencias/{competencia}/edit', [CompetenciaController::class, 'edit'])->name('competencias.edit');
        Route::put('/competencias/{competencia}', [CompetenciaController::class, 'update'])->name('competencias.update');
        Route::delete('/competencias/{competencia}', [CompetenciaController::class, 'destroy'])->name('competencias.destroy');
        Route::get('/competencias/trashed', [CompetenciaController::class, 'trashed'])->name('competencias.trashed');
        Route::post('/competencias/{id}/restore', [CompetenciaController::class, 'restore'])->name('competencias.restore');

        // Agrupar rutas estándar
        Route::resource('temporada-competencias', TemporadaCompetenciaController::class); // si no usas `show`

        Route::get('/temporada-competencias/{id}/equipos', [TemporadaCompetenciaController::class, 'equipos'])->name('temporada-competencias.equipos');

            // Rutas adicionales personalizadas
        Route::get('temporada-competencias/trashed', [TemporadaCompetenciaController::class, 'trashed'])->name('temporada-competencias.trashed');
        Route::post('temporada-competencias/{id}/restore', [TemporadaCompetenciaController::class, 'restore'])->name('temporada-competencias.restore');




        Route::post('/calendario/generar/{id}', [CalendarioController::class, 'generateByTemporadaCompetencia'])
            ->name('calendario.generar');


        Route::resource('temporada-equipos', TemporadaEquipoController::class)
            ->except(['show']); // si no usas `show`
        // Rutas adicionales personalizadas
        Route::get('temporada-equipos/trashed', [TemporadaEquipoController::class, 'trashed'])->name('temporada-equipos.trashed');
        Route::post('temporada-equipos/{id}/restore', [TemporadaEquipoController::class, 'restore'])->name('temporada-equipos.restore');


        Route::resource('temporada-equipos', TemporadaEquipoController::class);
    });



    Route::middleware('role:entrenador')->group(function () {
        Route::get('/entrenador/dashboard', fn () => Inertia::render('Entrenador/Dashboard'))->name('entrenador.dashboard');
    });

    Route::middleware('role:jugador')->group(function () {
        Route::get('/jugador/dashboard', fn () => Inertia::render('Jugador/Dashboard'))->name('jugador.dashboard');
    });
});


Route::get('/estadisticas/{tipo}', function ($tipo) {
    $data = [
        'goles' => [
            'titulo' => 'Goles',
            'color' => '#ff003c',
            'jugadores' => [
[
            'rank' => 1,
            'firstName' => 'Kylian',
            'lastName' => 'Mbappé',
            'playerImage' => '/images/jugadores/mbappe.png',
            'playerName' => 'Kylian Mbappé',
            'statValue' => 28,
            'clubLogo' => '/images/equipos/realmadrid.png',
            'clubName' => 'Real Madrid',
        ],
        [
            'rank' => 2,
            'firstName' => 'Erling',
            'lastName' => 'Haaland',
            'playerImage' => '/images/jugadores/haaland.png',
            'playerName' => 'Erling Haaland',
            'statValue' => 22,
            'clubLogo' => '/images/equipos/manchestercity.png',
            'clubName' => 'Manchester City',
        ],
        [
            'rank' => 3,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
        [
            'rank' => 4,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
        [
            'rank' => 5,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
            ],
        ],
        'asistencias' => [
            'titulo' => 'Asistencias',
            'color' => '#0099ff',
            'jugadores' => [
                [
            'rank' => 1,
            'firstName' => 'Kylian',
            'lastName' => 'Mbappé',
            'playerImage' => '/images/jugadores/mbappe.png',
            'playerName' => 'Kylian Mbappé',
            'statValue' => 28,
            'clubLogo' => '/images/equipos/realmadrid.png',
            'clubName' => 'Real Madrid',
        ],
        [
            'rank' => 2,
            'firstName' => 'Erling',
            'lastName' => 'Haaland',
            'playerImage' => '/images/jugadores/haaland.png',
            'playerName' => 'Erling Haaland',
            'statValue' => 22,
            'clubLogo' => '/images/equipos/manchestercity.png',
            'clubName' => 'Manchester City',
        ],
        [
            'rank' => 3,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
        [
            'rank' => 4,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
        [
            'rank' => 5,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
            ],
        ],
        'disparos' => [
            'titulo' => 'Disparos al arco',
            'color' => '#00cc66',
            'jugadores' => [
                [
            'rank' => 1,
            'firstName' => 'Kylian',
            'lastName' => 'Mbappé',
            'playerImage' => '/images/jugadores/mbappe.png',
            'playerName' => 'Kylian Mbappé',
            'statValue' => 28,
            'clubLogo' => '/images/equipos/realmadrid.png',
            'clubName' => 'Real Madrid',
        ],
        [
            'rank' => 2,
            'firstName' => 'Erling',
            'lastName' => 'Haaland',
            'playerImage' => '/images/jugadores/haaland.png',
            'playerName' => 'Erling Haaland',
            'statValue' => 22,
            'clubLogo' => '/images/equipos/manchestercity.png',
            'clubName' => 'Manchester City',
        ],
        [
            'rank' => 3,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
        [
            'rank' => 4,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
        [
            'rank' => 5,
            'firstName' => 'Robert',
            'lastName' => 'Lewandowski',
            'playerImage' => '/images/jugadores/lewandowski.png',
            'playerName' => 'Robert Lewandowski',
            'statValue' => 20,
            'clubLogo' => '/images/equipos/barcelona.png',
            'clubName' => 'FC Barcelona',
        ],
            ],
        ],
    ];

    $estadistica = $data[$tipo] ?? abort(404);

    return Inertia::render('Ligas/EstadisticasTableView', [
        'titulo' => $estadistica['titulo'],
        'backgroundColor' => $estadistica['color'],
        'jugadores' => $estadistica['jugadores'],
    ]);
});

require __DIR__.'/auth.php';
