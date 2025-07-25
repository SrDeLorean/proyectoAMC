<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Entrenador\DashboardController;
use App\Http\Controllers\Entrenador\EquipoController;
use App\Http\Controllers\Entrenador\TraspasoController;
use App\Http\Controllers\Entrenador\PlantillaController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/equipos', [\App\Http\Controllers\Entrenador\EquipoController::class, 'index'])
    ->name('equipos.index');

Route::get('/traspasos', [TraspasoController::class, 'index'])->name('traspasos.index');
Route::get('/traspasos/create', [TraspasoController::class, 'create'])->name('traspasos.create');
Route::post('/traspasos', [TraspasoController::class, 'store'])->name('traspasos.store');

Route::prefix('plantillas')->group(function () {
    Route::get('/', [PlantillaController::class, 'index'])->name('plantillas.index');
    Route::get('{id}/edit', [PlantillaController::class, 'edit'])->name('plantillas.edit');
    Route::put('{id}', [PlantillaController::class, 'update'])->name('plantillas.update');
});
