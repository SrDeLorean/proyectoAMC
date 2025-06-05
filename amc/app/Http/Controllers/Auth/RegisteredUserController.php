<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Traits\RedirectsUsersByRole;

class RegisteredUserController extends Controller
{

    use RedirectsUsersByRole;

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_ea'    => 'required|string|max:255|unique:users,id_ea',
            // 'foto'     => 'nullable|image|mimes:png,jpeg,png|max:2048',
        ]);

        $fotoPath = 'images/users/default-user.png';

        /*
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('users', 'public');
        }
        */

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'jugador',
            'id_ea'    => $request->id_ea,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->redirectToByRole());
    }
}
