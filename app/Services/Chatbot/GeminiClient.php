<?php

namespace App\Services\Chatbot;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cliente mínimo para la API de Google AI Studio (Gemini).
 *
 * Única clase que conoce al proveedor: si mañana se cambia Gemini por
 * OpenAI u Ollama, solo se reescribe esta clase y el resto del chatbot
 * (base de conocimiento, prompt, controlador, vista) queda igual.
 *
 * La API key vive SOLO en el servidor (.env -> config/services.php);
 * nunca se envía al navegador.
 */
class GeminiClient
{
    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models';

    private readonly ?string $apiKey;

    private readonly string $model;

    private readonly int $timeout;

    /**
     * Sin argumentos toma los valores de config/services.php (que a su vez lee el .env).
     * Así el contenedor de Laravel puede inyectarlo automáticamente.
     */
    public function __construct(?string $apiKey = null, ?string $model = null, ?int $timeout = null)
    {
        $this->apiKey = $apiKey ?? config('services.gemini.key');
        $this->model = $model ?? config('services.gemini.model', 'gemini-flash-latest');
        $this->timeout = $timeout ?? (int) config('services.gemini.timeout', 15);
    }

    public function estaConfigurado(): bool
    {
        return filled($this->apiKey);
    }

    /**
     * @param  string  $systemPrompt  Instrucciones de sistema (rol, reglas, base de conocimiento)
     * @param  array<int, array{role: string, text: string}>  $turnos  Conversación (few-shot + historial + mensaje actual)
     *
     * @throws ChatbotException
     */
    public function generar(string $systemPrompt, array $turnos): string
    {
        if (! $this->estaConfigurado()) {
            throw new ChatbotException('El asistente no está configurado (falta GEMINI_API_KEY).', 503);
        }

        $payload = [
            'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
            'contents' => array_map(fn (array $t) => [
                'role' => $t['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $t['text']]],
            ], $turnos),
            'generationConfig' => [
                // Temperatura baja: respuestas consistentes y pegadas a la base de conocimiento
                'temperature' => 0.3,
                'maxOutputTokens' => 1024,
            ],
        ];

        // Se prueba el modelo principal y, si está saturado o caído, los de respaldo.
        $ultimoError = null;
        $respuesta = null;

        foreach ($this->modelos() as $modelo) {
            try {
                $respuesta = $this->llamar($modelo, $payload);
            } catch (ConnectionException $e) {
                Log::warning('Chatbot: sin conexión con Gemini', ['modelo' => $modelo, 'error' => $e->getMessage()]);
                $ultimoError = new ChatbotException('El asistente tardó demasiado en responder. Intenta de nuevo en un momento.', 504);

                continue;
            }

            if ($respuesta->successful()) {
                break;
            }

            // Se registra el estado y el mensaje de error de Google, nunca la API key
            Log::warning('Chatbot: error de Gemini', [
                'modelo' => $modelo,
                'status' => $respuesta->status(),
                'error' => $respuesta->json('error.message'),
            ]);

            // 429 (cuota), 500/503 (modelo saturado) y 404 (modelo no disponible): probar el siguiente
            if (in_array($respuesta->status(), [404, 429, 500, 503], true)) {
                $ultimoError = $respuesta->status() === 429
                    ? new ChatbotException('El asistente está recibiendo muchas consultas. Espera unos segundos e intenta otra vez.', 429)
                    : new ChatbotException('El asistente está muy ocupado en este momento. Intenta de nuevo en unos segundos.', 503);
                $respuesta = null;

                continue;
            }

            // 400/401/403: error de configuración (clave inválida, petición mal formada); no sirve reintentar
            throw new ChatbotException('El asistente no está disponible en este momento.', 502);
        }

        if (empty($respuesta)) {
            throw $ultimoError ?? new ChatbotException('El asistente no está disponible en este momento.', 502);
        }

        $texto = collect($respuesta->json('candidates.0.content.parts', []))
            ->pluck('text')
            ->filter()
            ->implode('');

        if (trim($texto) === '') {
            throw new ChatbotException('No obtuve una respuesta. ¿Puedes reformular tu pregunta?', 502);
        }

        return trim($texto);
    }

    /**
     * Modelo principal + modelos de respaldo (GEMINI_FALLBACK_MODELS), sin repetidos.
     *
     * @return array<int, string>
     */
    private function modelos(): array
    {
        $respaldo = array_map('trim', explode(',', (string) config('services.gemini.fallback_models', '')));

        return array_values(array_unique(array_filter([$this->model, ...$respaldo])));
    }

    /**
     * Una llamada a un modelo, con un reintento corto si Google responde 503 (saturado).
     */
    private function llamar(string $modelo, array $payload): Response
    {
        return Http::withHeaders(['X-goog-api-key' => $this->apiKey])
            ->acceptJson()
            ->timeout($this->timeout)
            ->retry(2, 800, fn ($e) => $e instanceof RequestException && $e->response->status() === 503, throw: false)
            ->post(self::BASE_URL."/{$modelo}:generateContent", $payload);
    }
}
