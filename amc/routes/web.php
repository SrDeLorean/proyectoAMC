<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EquipoController;

use App\Http\Controllers\Admin\UserController;

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
