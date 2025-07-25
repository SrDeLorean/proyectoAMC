<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Guest\CompetenciaController;
use App\Http\Controllers\Guest\EquipoController;
use App\Http\Controllers\Admin\CalendarioController;

Route::get('/competencias', [CompetenciaController::class, 'index'])->name('competencias.index');
Route::get('/competencias/{id}', [CompetenciaController::class, 'show'])->name('competencias.show');
Route::get('/calendario/{id}', [CalendarioController::class, 'show'])->name('calendario.show');

Route::get('/equipos/{id}', [EquipoController::class, 'show'])->name('equipos.show');
