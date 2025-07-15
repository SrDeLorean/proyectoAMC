<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Página de bienvenida (usando controlador)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Rutas públicas (sin autenticación)
Route::middleware(['guest'])
    ->group(base_path('routes/guest.php'));

// Rutas para administrador (autenticado y con rol)
Route::middleware(['auth', 'role:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

// Rutas para entrenador (autenticado y con rol)
Route::middleware(['auth', 'role:entrenador'])
    ->prefix('entrenador')
    ->name('entrenador.')
    ->group(base_path('routes/entrenador.php'));

// Rutas para jugador (autenticado y con rol)
Route::middleware(['auth', 'role:jugador'])
    ->prefix('jugador')
    ->name('jugador.')
    ->group(base_path('routes/jugador.php'));

// Rutas compartidas para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirección automática al dashboard según rol
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'administrador' => redirect()->route('admin.dashboard'),
            'entrenador'    => redirect()->route('entrenador.dashboard'),
            'jugador'       => redirect()->route('jugador.dashboard'),
            default         => redirect()->route('dashboard'),
        };
    })->name('dashboard');
});

// Rutas de autenticación (login, register, etc.)
require __DIR__.'/auth.php';
