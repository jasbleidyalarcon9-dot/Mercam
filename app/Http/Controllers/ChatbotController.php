<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatbotMensajeRequest;
use App\Services\Chatbot\AsistenteMercam;
use App\Services\Chatbot\ChatbotException;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    public function __construct(private readonly AsistenteMercam $asistente) {}

    /**
     * POST /asistente/mensaje
     * Recibe { mensaje, historial[] } y devuelve { respuesta }.
     */
    public function mensaje(ChatbotMensajeRequest $request): JsonResponse
    {
        $datos = $request->validated();

        // Solo se comparte el TIPO de usuario para adaptar las respuestas;
        // ni nombre, ni correo, ni pedidos llegan al modelo.
        $rol = $request->user()?->role?->name ?? 'visitante';

        try {
            $respuesta = $this->asistente->responder(
                trim($datos['mensaje']),
                $datos['historial'] ?? [],
                $rol,
            );
        } catch (ChatbotException $e) {
            return response()->json(['error' => $e->getMessage()], $e->status());
        }

        return response()->json(['respuesta' => $respuesta]);
    }
}
