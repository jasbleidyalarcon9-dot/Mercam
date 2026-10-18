<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('perfil.password');
    }

    public function update(UpdatePasswordRequest $request)
    {
        $data = $request->validated();

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
