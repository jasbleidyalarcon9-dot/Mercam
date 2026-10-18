<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida que el DOMINIO del correo (lo que va después de la @) exista y
 * tenga servidores configurados para recibir correo (registro MX o, en su
 * defecto, registro A). Esto detecta typos como "gmial.com" o dominios
 * completamente inventados, EN EL MOMENTO, sin enviar ningún correo.
 *
 * Lo que esta regla NO puede detectar: que el buzón específico
 * (samecogomez9865@gmail.com) exista de verdad dentro de un dominio real
 * como gmail.com. Eso ningún proveedor lo deja consultar por adelantado
 * (por seguridad, para que no se puedan "adivinar" correos ajenos) — la
 * única forma confiable de confirmar ESO es la verificación por correo que
 * ya tiene la app (MustVerifyEmail): si el buzón no existe, el enlace nunca
 * se abre y la cuenta se queda sin activar para siempre.
 */
class DominioDeCorreoValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dominio = substr((string) strrchr((string) $value, '@'), 1);

        if (!$dominio) {
            return; // la regla 'email' ya se encarga de este caso
        }

        if (!checkdnsrr($dominio, 'MX') && !checkdnsrr($dominio, 'A')) {
            $fail('El dominio de ese correo ("'.$dominio.'") no existe o no puede recibir correos. Revisa que esté bien escrito.');
        }
    }
}