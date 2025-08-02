<?php
namespace App\Http\Controllers\Jugador;

use App\Http\Controllers\Controller;
use App\Models\Traspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraspasoController extends Controller
{
    // Listar ofertas para el jugador
    public function index()
    {
        $user = Auth::user();

        $traspasos = Traspaso::with(['equipoOrigen', 'equipoDestino'])
            ->where('id_jugador', $user->id)
            ->whereIn('estado', ['pendiente', 'aceptado'])
            ->get();

        return inertia('Jugador/Traspasos/Index', compact('traspasos'));
    }

    // Aceptar oferta
    public function aceptar($id)
    {
        $user = Auth::user();

        $traspaso = Traspaso::where('id', $id)
            ->where('id_jugador', $user->id)
            ->where('estado', 'pendiente')
            ->firstOrFail();

        $traspaso->update([
            'estado' => 'aceptado',
            'fecha_respuesta_jugador' => now(),
        ]);

        return back()->with('success', 'Has aceptado la oferta de fichaje.');
    }

    // Rechazar oferta
    public function rechazar($id)
    {
        $user = Auth::user();

        $traspaso = Traspaso::where('id', $id)
            ->where('id_jugador', $user->id)
            ->where('estado', 'pendiente')
            ->firstOrFail();

        $traspaso->update([
            'estado' => 'rechazado',
            'fecha_respuesta_jugador' => now(),
        ]);

        return back()->with('success', 'Has rechazado la oferta de fichaje.');
    }
}
