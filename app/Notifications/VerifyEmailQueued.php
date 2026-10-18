<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Igual a la notificación de verificación que trae Laravel por defecto,
 * pero encolada: si el correo se demora o el SMTP falla, NO bloquea el
 * registro del usuario (antes, un problema de correo tumbaba todo el
 * request con un error 500 después de 60 segundos esperando el SMTP).
 */
class VerifyEmailQueued extends VerifyEmailBase implements ShouldQueue
{
    use Queueable;
}