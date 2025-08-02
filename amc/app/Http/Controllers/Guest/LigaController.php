<?php


namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller; // ✅ Asegúrate de importar esto
use App\Models\Competencia;
use Inertia\Inertia;

class LigaController extends Controller
{
    public function index()
    {
        $competencias = Competencia::all();

        return Inertia::render('Guest/Competencias/Index', [
            'competencias' => $competencias,
        ]);
    }
}
