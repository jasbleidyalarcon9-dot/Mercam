<?php
 
namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
 
class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }
 
    public function store(RegisterUserRequest $request)
    {
        // Límite por IP: máximo 5 registros cada 10 minutos, para frenar
        // creación automatizada de cuentas (igual que el login).
        $throttleKey = 'registro|'.Str::transliterate($request->ip());
 
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $segundos = RateLimiter::availableIn($throttleKey);
 
            throw ValidationException::withMessages([
                'email' => "Demasiados registros desde aquí. Intenta de nuevo en {$segundos} segundos.",
            ]);
        }
 
        RateLimiter::hit($throttleKey, 600);
 
        $data = $request->validated();
 
        // El rol se resuelve en el servidor a partir de lo elegido en el
        // formulario ("productor" o "consumidor"); "administrador" jamás
        // puede llegar por aquí porque la regla de validación solo acepta
        // esos dos valores.
        $rol = Role::where('name', $data['tipo_cuenta'])->firstOrFail();
 
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'datos_aceptados_en' => now(),
        ]);
 
        // role_id se asigna directo
        $user->role_id = $rol->id; // asignación directa
        $user->save();
 
        RateLimiter::clear($throttleKey);
        auth()->login($user);
 
        // Dispara el correo de "confirma tu cuenta" (notificación nativa de
        // Laravel, MustVerifyEmail). Hasta que el usuario no le dé clic al
        // enlace, el middleware 'verified' le bloquea el resto del sitio.
        $user->sendEmailVerificationNotification();
 
        return redirect()->route('verification.notice');
    }
}