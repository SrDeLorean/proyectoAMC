<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Guest\CompetenciaController;
use App\Http\Controllers\Guest\EquipoController;
use App\Http\Controllers\Admin\CalendarioController;
use App\Http\Controllers\Guest\JugadorController;

Route::get('/competencias', [CompetenciaController::class, 'index'])->name('competencias.index');
Route::get('/competencias/{id}', [CompetenciaController::class, 'show'])->name('competencias.show');
Route::get('/calendario/{id}', [CalendarioController::class, 'show'])->name('calendario.show');




// jugadores index y equipos index
Route::get('/jugadores', [JugadorController::class, 'index'])->name('jugadores.index');
Route::get('/jugadores/{id}', [JugadorController::class, 'show'])->name('jugadores.show');

Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
Route::get('/equipos/{id}', [EquipoController::class, 'show'])->name('equipos.show');

