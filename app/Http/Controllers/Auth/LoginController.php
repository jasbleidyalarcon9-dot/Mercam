<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($credentials['email']).'|'.$request->ip());

        // Máximo 5 intentos fallidos antes de bloquear temporalmente el login
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $segundos = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Intenta de nuevo en {$segundos} segundos.",
            ]);
        }

        if (!auth()->attempt($credentials)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Credenciales incorrectas',
            ]);
        }

        if (auth()->user()->estaSuspendido()) {
            auth()->logout();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está suspendida. Contacta al administrador de Mercampo.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}