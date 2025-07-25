<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Jugador\DashboardController;
use App\Http\Controllers\Jugador\EquipoController;
use App\Http\Controllers\Jugador\TraspasoController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/equipos', [EquipoController::class, 'index'])
    ->name('equipos.index');


Route::get('/traspasos', [TraspasoController::class, 'index'])->name('traspasos.index');
Route::post('/traspasos/{id}/aceptar', [TraspasoController::class, 'aceptar'])->name('traspasos.aceptar');
Route::post('/traspasos/{id}/rechazar', [TraspasoController::class, 'rechazar'])->name('traspasos.rechazar');
