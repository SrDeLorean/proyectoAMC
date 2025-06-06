<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private const DEFAULT_foto = 'images/users/default-user.png';

    public function index()
    {
        $users = User::all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            // Pasa el flash explícitamente desde sesión
            'success' => Session::get('success'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'id_ea'    => 'nullable|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:jugador,entrenador,administrador',
            'foto'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('images/users'), $filename);
            $fotoPath = 'images/users/' . $filename;
        } else {
            $fotoPath = self::DEFAULT_foto;
        }

        User::create([
            'name'     => $request->name,
            'id_ea'    => $request->id_ea,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'foto'     => $fotoPath,
        ]);

        // Flash manual usando Session::flash
        Session::flash('success', 'Usuario creado correctamente.');

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', Rule::in(['jugador', 'entrenador', 'administrador'])],
            'id_ea' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => 'image|max:2048',
            ]);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];
        $user->id_ea = $validated['id_ea'] ?? null;

        if ($request->hasFile('foto')) {
            if ($user->foto && $user->foto !== self::DEFAULT_foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $foto = $request->file('foto');
            $filename = uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('images/users'), $filename);
            $user->foto = 'images/users/' . $filename;
        }

        $user->save();

        // Aquí puedes usar with() o Session::flash, pero para coherencia con store, cambia a Session::flash
        Session::flash('success', 'Usuario actualizado correctamente.');

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        Session::flash('success', 'Usuario eliminado correctamente.');

        return redirect()->route('admin.users.index');
    }

    public function trashed()
    {
        $trashedUsers = User::onlyTrashed()->get();

        return Inertia::render('Admin/Users/Trashed', [
            'users' => $trashedUsers,
            'success' => Session::get('success'),
        ]);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        Session::flash('success', 'Usuario restaurado correctamente.');

        return redirect()->route('admin.users.trashed');
    }
}
