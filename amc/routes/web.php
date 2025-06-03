<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
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
    });

    Route::middleware('role:entrenador')->group(function () {
        Route::get('/entrenador/dashboard', fn () => Inertia::render('Entrenador/Dashboard'))->name('entrenador.dashboard');
    });

    Route::middleware('role:jugador')->group(function () {
        Route::get('/jugador/dashboard', fn () => Inertia::render('Jugador/Dashboard'))->name('jugador.dashboard');
    });
});

require __DIR__.'/auth.php';
