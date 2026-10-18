{{--
    Ventana de chat reutilizable del asistente "Campi".
    Se usa en dos lugares con el mismo código:
      - <x-chatbot modo="flotante" />  -> burbuja en la esquina de todas las páginas (layouts/app)
      - <x-chatbot modo="pagina" />    -> chat a pantalla completa en /asistente
    Toda la lógica vive en public/js/chatbot.js; los estilos en public/css/chatbot.css.
--}}
@props(['modo' => 'flotante'])

@php
    $sugerencias = auth()->check() && in_array(auth()->user()->role?->name, ['productor', 'administrador'])
        ? ['¿Cómo publico un producto?', '¿Cómo cambio el estado de un pedido?', '¿Qué productos hay publicados?']
        : ['¿Qué frutas tienen?', '¿Cómo compro un producto?', '¿Cómo me registro como productor?'];
@endphp

<div class="campi campi--{{ $modo }}"
     data-chatbot
     data-endpoint="{{ route('asistente.mensaje') }}"
     @if ($modo === 'flotante') data-flotante @endif>

    @if ($modo === 'flotante')
        <button type="button" class="campi__lanzador" data-chatbot-abrir aria-expanded="false" aria-controls="campi-ventana">
            <span aria-hidden="true">🌱</span>
            <span class="campi__lanzador-texto">¿Te ayudo?</span>
        </button>
    @endif

    <section class="campi__ventana" id="{{ $modo === 'flotante' ? 'campi-ventana' : 'campi-pagina' }}"
             @if ($modo === 'flotante') hidden @endif
             aria-label="Asistente virtual Campi">
        <header class="campi__cabecera">
            <div class="campi__avatar" aria-hidden="true">🌱</div>
            <div class="campi__titulo">
                <strong>Campi</strong>
                <small>Asistente virtual con IA de Mercam</small>
            </div>
            <button type="button" class="campi__icono" data-chatbot-reiniciar title="Nueva conversación" aria-label="Nueva conversación">↺</button>
            @if ($modo === 'flotante')
                <button type="button" class="campi__icono" data-chatbot-cerrar title="Cerrar" aria-label="Cerrar asistente">✕</button>
            @endif
        </header>

        <div class="campi__mensajes" data-chatbot-mensajes aria-live="polite">
            <div class="campi__burbuja campi__burbuja--bot">
                ¡Hola! Soy <strong>Campi</strong> 🌱, el asistente de Mercam. Puedo ayudarte a encontrar productos del campo
                y a usar la plataforma. Soy una inteligencia artificial: verifica siempre precios y stock en la página del producto.
            </div>
        </div>

        <div class="campi__sugerencias" data-chatbot-sugerencias>
            @foreach ($sugerencias as $s)
                <button type="button" class="campi__chip">{{ $s }}</button>
            @endforeach
        </div>

        <form class="campi__formulario" data-chatbot-form>
            <label for="campi-input-{{ $modo }}" class="visually-hidden">Escribe tu pregunta</label>
            <input id="campi-input-{{ $modo }}" type="text" name="mensaje" maxlength="500" autocomplete="off"
                   placeholder="Escribe tu pregunta..." required>
            <button type="submit" class="campi__enviar" aria-label="Enviar">➤</button>
        </form>

        <p class="campi__aviso">
            No compartas contraseñas ni datos personales.
            <a href="{{ route('asistente.index') }}#etica">¿Cómo funciona Campi?</a>
        </p>
    </section>
</div>
