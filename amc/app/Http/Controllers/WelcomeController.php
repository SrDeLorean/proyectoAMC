<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $competencias = Competencia::get(['id', 'nombre', 'logo']);

        return Inertia::render('Welcome', [
            'competencias'   => $competencias,
            'canLogin'       => \Route::has('login'),
            'canRegister'    => \Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion'     => PHP_VERSION,
        ]);
    }
}
