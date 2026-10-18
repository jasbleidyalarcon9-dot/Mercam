<?php

namespace App\Http\Requests;

use App\Services\Chatbot\AsistenteMercam;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación en servidor del mensaje que llega desde el widget del chat.
 */
class ChatbotMensajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El asistente es público (visitantes incluidos)
    }

    public function rules(): array
    {
        return [
            'mensaje' => ['required', 'string', 'max:500'],
            'historial' => ['sometimes', 'array', 'max:'.(AsistenteMercam::MAX_HISTORIAL * 2)],
            'historial.*.role' => ['required', 'string', 'in:user,model'],
            'historial.*.text' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'mensaje.required' => 'Escribe una pregunta para el asistente.',
            'mensaje.max' => 'Tu pregunta es muy larga (máximo 500 caracteres).',
        ];
    }
}
