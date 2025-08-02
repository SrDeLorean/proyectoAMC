<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Entrenador\DashboardController;
use App\Http\Controllers\Entrenador\EquipoController;
use App\Http\Controllers\Entrenador\TraspasoController;
use App\Http\Controllers\Entrenador\PlantillaController;
use App\Http\Controllers\Entrenador\CompetenciaController;
use App\Http\Controllers\Entrenador\ReporteController;


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/dashboard/{user}', [DashboardController::class, 'show'])
    ->name('dashboard.show');


Route::get('/equipos', [\App\Http\Controllers\Entrenador\EquipoController::class, 'index'])
    ->name('equipos.index');
//edit equipo
Route::get('/equipos/{id}/edit', [EquipoController::class, 'edit'])
    ->name('equipos.edit');
//update equipo
Route::post('equipos/{equipo}', [EquipoController::class, 'update'])->name('update');


Route::get('/traspasos', [TraspasoController::class, 'index'])->name('traspasos.index');
Route::get('/traspasos/create', [TraspasoController::class, 'create'])->name('traspasos.create');
Route::post('/traspasos', [TraspasoController::class, 'store'])->name('traspasos.store');

Route::prefix('plantillas')->group(function () {
    Route::get('/', [PlantillaController::class, 'index'])->name('plantillas.index');
    Route::get('{id}/edit', [PlantillaController::class, 'edit'])->name('plantillas.edit');
    Route::put('{id}', [PlantillaController::class, 'update'])->name('plantillas.update');
    Route::delete('{id}', [PlantillaController::class, 'destroy'])->name('plantillas.destroy');
});


Route::get('/competencias', [CompetenciaController::class, 'index'])->name('competencias.index');
Route::get('/competencias/{id}', [CompetenciaController::class, 'show'])->name('competencias.show');

Route::get('/reportes/create/{calendario}', [ReporteController::class, 'create'])
    ->name('reportes.create');

Route::post('/reportes/store/{calendario}', [ReporteController::class, 'store'])
    ->name('reportes.store');
