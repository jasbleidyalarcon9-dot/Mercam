<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('perfil.mostrar', ['user' => auth()->user()]);
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();
        $user->name = trim($request->name);
        $user->save();

        return back()->with('success', 'Nombre actualizado correctamente.');
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        $user = auth()->user();

        // Borrar imagen anterior (si existe)
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Guardar nueva imagen
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'Avatar actualizado');
    }
}
