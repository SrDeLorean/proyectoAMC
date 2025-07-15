<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Competencia;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class CompetenciaController extends Controller
{

    private const DEFAULT_LOGO = 'images/competencias/default-logo.png';

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Competencia::query();

        if ($search) {
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        }

        $competencias = $query->latest()->paginate($perPage)->withQueryString();

        return inertia('Admin/Competencias/Index', [
            'competencias' => $competencias,
            'filters' => [
                'search' => $search,
                'perPage' => $perPage,
            ],
            'success' => Session::get('success'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Competencias/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'logo'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/competencias'), $filename);
            $logoPath = 'images/competencias/' . $filename;
        } else {
            $logoPath = self::DEFAULT_LOGO;
        }

        Competencia::create([
            'nombre' => $request->nombre,
            'logo'   => $logoPath,
        ]);

        Session::flash('success', 'Competencia creada correctamente.');

        return redirect()->route('competencias.index');
    }

    public function edit(Competencia $competencia)
    {
        return Inertia::render('Admin/Competencias/Edit', [
            'competencia' => $competencia,
        ]);
    }

    public function update(Request $request, Competencia $competencia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'logo'   => 'nullable|image|max:2048',
        ]);

        // Manejo del logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            // Eliminar logo anterior si no es el default
            if (
                $competencia->logo &&
                $competencia->logo !== self::DEFAULT_LOGO &&
                file_exists(public_path($competencia->logo))
            ) {
                unlink(public_path($competencia->logo));
            }

            $filename = uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/competencias'), $filename);
            $competencia->logo = 'images/competencias/' . $filename;
        }

        $competencia->nombre = $validated['nombre'];
        $competencia->save();

        Session::flash('success', 'Competencia actualizada correctamente.');

        return redirect()->route('competencias.index');
    }

    public function destroy(Competencia $competencia)
    {
        $competencia->delete();

        return redirect()
            ->route('competencias.index')
            ->with('success', 'Competencia eliminada correctamente.');
    }

    public function trashed()
    {
        $competencias = Competencia::onlyTrashed()->get();

        return Inertia::render('Admin/Competencias/Trashed', [
            'competencias' => $competencias,
            'success' => session('success'),
        ]);
    }

    public function restore($id)
    {
        $competencia = Competencia::onlyTrashed()->findOrFail($id);
        $competencia->restore();

        return redirect()
            ->route('competencias.trashed')
            ->with('success', 'Competencia restaurada correctamente.');
    }
}
