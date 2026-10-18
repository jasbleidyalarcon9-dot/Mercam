<?php
 
namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
 
class VerifyEmailController extends Controller
{
    // Pantalla que ve el usuario mientras su correo no esté confirmado.
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
 
        return view('auth.verify-email');
    }
 
    // El enlace del correo llega FIRMADO (Laravel valida la firma solo,
    // gracias al middleware 'signed') y con throttle contra fuerza bruta.
    public function verify(EmailVerificationRequest $request)
    {
        // $request->fulfill() ya viene resuelto por EmailVerificationRequest:
        // valida que el hash del enlace corresponda a ESTE usuario y a ESTE
        // correo, y marca email_verified_at. No se puede usar el enlace de
        // un usuario para verificar la cuenta de otro.
        $request->fulfill();
 
        return redirect()->route('dashboard')->with('success', '¡Tu correo quedó confirmado!');
    }
 
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
 
        // Máximo 3 reenvíos cada 5 minutos, para no dejar que alguien
        // convierta esto en una forma de mandar spam a un correo ajeno.
        $throttleKey = 'verificar-correo|'.$request->user()->id;
 
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $segundos = RateLimiter::availableIn($throttleKey);
 
            return back()->with('error', "Espera {$segundos} segundos antes de pedir otro enlace.");
        }
 
        RateLimiter::hit($throttleKey, 300);
 
        $request->user()->sendEmailVerificationNotification();
 
        return back()->with('success', 'Te reenviamos el enlace de confirmación.');
    }
}