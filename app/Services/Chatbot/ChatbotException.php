<?php

namespace App\Services\Chatbot;

use RuntimeException;

/**
 * Error controlado del asistente: el mensaje es seguro para mostrarlo al usuario
 * y el código se usa como estado HTTP de la respuesta.
 */
class ChatbotException extends RuntimeException
{
    public function __construct(string $message, private readonly int $status = 500)
    {
        parent::__construct($message);
    }

    public function status(): int
    {
        return $this->status;
    }
}
