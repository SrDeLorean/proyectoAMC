<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controladores Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlantillaController;
use App\Http\Controllers\Admin\TemporadaController;
use App\Http\Controllers\Admin\CompetenciaController;
use App\Http\Controllers\Admin\TemporadaCompetenciaController;
use App\Http\Controllers\Admin\CalendarioController;
use App\Http\Controllers\Admin\TemporadaEquipoController;
use App\Http\Controllers\Admin\EquipoController; // <- ESTA ES LA NUEVA IMPORTACIÓN
use App\Http\Controllers\Admin\EstadisticaEquipoController;

// Dashboard
Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard');

// Usuarios
Route::prefix('usuarios')->name('users.')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{user}/edit', 'edit')->name('edit');
    Route::post('/{user}', 'update')->name('update');
    Route::delete('/{user}', 'destroy')->name('destroy');
    Route::get('/trashed', 'trashed')->name('trashed');
    Route::post('/{id}/restore', 'restore')->name('restore');
});

// Plantillas
Route::prefix('plantillas')->name('plantillas.')->controller(PlantillaController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{plantilla}/edit', 'edit')->name('edit');
    Route::put('/{plantilla}', 'update')->name('update');
    Route::delete('/{plantilla}', 'destroy')->name('destroy');
    Route::get('/trashed', 'trashed')->name('trashed');
    Route::post('/{id}/restore', 'restore')->name('restore');
});

// Temporadas
Route::prefix('temporadas')->name('temporadas.')->controller(TemporadaController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{temporada}/edit', 'edit')->name('edit');
    Route::put('/{temporada}', 'update')->name('update');
    Route::delete('/{temporada}', 'destroy')->name('destroy');
    Route::get('/trashed', 'trashed')->name('trashed');
    Route::post('/{id}/restore', 'restore')->name('restore');
});

// Competencias
Route::prefix('competencias')->name('competencias.')->controller(CompetenciaController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{competencia}/edit', 'edit')->name('edit');
    Route::put('/{competencia}', 'update')->name('update');
    Route::delete('/{competencia}', 'destroy')->name('destroy');
    Route::get('/trashed', 'trashed')->name('trashed');
    Route::post('/{id}/restore', 'restore')->name('restore');
});

// Temporada Competencias
Route::resource('temporada-competencias', TemporadaCompetenciaController::class);
Route::get('temporada-competencias/{id}/equipos', [TemporadaCompetenciaController::class, 'equipos'])->name('temporada-competencias.equipos');
Route::get('temporada-competencias/trashed', [TemporadaCompetenciaController::class, 'trashed'])->name('temporada-competencias.trashed');
Route::post('temporada-competencias/{id}/restore', [TemporadaCompetenciaController::class, 'restore'])->name('temporada-competencias.restore');

// Calendarios
Route::resource('calendarios', CalendarioController::class);
Route::get('calendarios/trashed', [CalendarioController::class, 'trashed'])->name('calendarios.trashed');
Route::post('calendarios/restore/{id}', [CalendarioController::class, 'restore'])->name('calendarios.restore');
Route::post('/calendario/generar/{id}', [CalendarioController::class, 'generateByTemporadaCompetencia'])->name('calendario.generar');
Route::post('/calendario/generar-solo-ida/{id}', [CalendarioController::class, 'generateSoloIdaByTemporadaCompetencia'])->name('calendario.generar-ida');

// Temporada Equipos
Route::resource('temporada-equipos', TemporadaEquipoController::class)->except(['show']);
Route::get('temporada-equipos/trashed', [TemporadaEquipoController::class, 'trashed'])->name('temporada-equipos.trashed');
Route::post('temporada-equipos/{id}/restore', [TemporadaEquipoController::class, 'restore'])->name('temporada-equipos.restore');

// Equipos (agregado)
Route::prefix('equipos')->name('equipos.')->group(function () {
    Route::get('/', [EquipoController::class, 'index'])->name('index');
    Route::get('/create', [EquipoController::class, 'create'])->name('create');
    Route::post('/', [EquipoController::class, 'store'])->name('store');
    Route::get('/{equipo}/edit', [EquipoController::class, 'edit'])->name('edit');
    Route::post('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
    Route::get('/trashed', [EquipoController::class, 'trashed'])->name('trashed');
    Route::post('/{id}/restore', [EquipoController::class, 'restore'])->name('restore');
});


Route::post('/estadisticas/subir', [EstadisticaEquipoController::class, 'subirDesdeImagen'])->name('estadisticas.subir');
