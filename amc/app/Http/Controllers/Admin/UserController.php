<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    // Ruta de la imagen por defecto (ubicada en public/)
    private const DEFAULT_PHOTO = 'images/users/default-user.png';

    public function index()
    {
        $users = User::all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
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
            'photo'    => 'nullable|image|max:2048',
        ]);

        // Si se sube una imagen, se guarda en public/images/users (usando move)
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/users'), $filename);
            $photoPath = 'images/users/' . $filename;
        } else {
            $photoPath = self::DEFAULT_PHOTO;
        }

        User::create([
            'name'     => $request->name,
            'id_ea'    => $request->id_ea,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'foto'     => $photoPath,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
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
            'photo' => 'nullable|image|max:2048',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];
        $user->id_ea = $validated['id_ea'] ?? null;

        if ($request->hasFile('photo')) {
            // Elimina la imagen anterior si no es la por defecto
            if ($user->foto && $user->foto !== self::DEFAULT_PHOTO && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            $photo = $request->file('photo');
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/users'), $filename);
            $user->foto = 'images/users/' . $filename;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function trashed()
    {
        $trashedUsers = User::onlyTrashed()->get();

        return Inertia::render('Admin/Users/Trashed', [
            'users' => $trashedUsers,
        ]);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trashed')->with('success', 'Usuario restaurado correctamente.');
    }
}
