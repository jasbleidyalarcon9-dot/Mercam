/**
 * Widget del asistente "Campi" (Mercam).
 *
 * Se engancha a cualquier elemento con [data-chatbot], así el mismo código
 * sirve para la burbuja flotante y para la página /asistente.
 * Habla SOLO con nuestro backend Laravel (POST /asistente/mensaje); la API key
 * de Gemini nunca llega al navegador.
 */
(function () {
    'use strict';

    const CLAVE_HISTORIAL = 'mercam.campi.historial';
    const MAX_HISTORIAL = 20; // mensajes (10 turnos de usuario + 10 del bot)
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    // ---------- Historial compartido (sobrevive al cambiar de página) ----------
    const almacen = {
        leer() {
            try { return JSON.parse(sessionStorage.getItem(CLAVE_HISTORIAL)) ?? []; } catch { return []; }
        },
        guardar(historial) {
            try { sessionStorage.setItem(CLAVE_HISTORIAL, JSON.stringify(historial.slice(-MAX_HISTORIAL))); } catch { /* sin almacenamiento: se queda en memoria */ }
        },
        borrar() {
            try { sessionStorage.removeItem(CLAVE_HISTORIAL); } catch { /* nada */ }
        },
    };

    // ---------- Utilidades ----------
    function escaparHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    /** Formato mínimo y seguro: escapa todo y luego habilita **negrita**, enlaces y saltos de línea. */
    function formatear(texto) {
        return escaparHTML(texto)
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/(https?:\/\/[^\s<)"']+)/g, '<a href="$1" target="_blank" rel="noopener">ver producto</a>')
            .replace(/^\s*[-*]\s+/gm, '• ')
            .replace(/\n/g, '<br>');
    }

    async function enviarAlServidor(endpoint, mensaje, historial) {
        const controlador = new AbortController();
        const temporizador = setTimeout(() => controlador.abort(), 60000);

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ mensaje, historial }),
                signal: controlador.signal,
            });

            const datos = await res.json().catch(() => ({}));

            if (res.status === 429 && !datos.error) {
                throw new Error('Estás enviando muchos mensajes seguidos. Espera un minuto e intenta de nuevo.');
            }
            if (res.status === 419) {
                throw new Error('Tu sesión expiró. Recarga la página e intenta otra vez.');
            }
            if (!res.ok) {
                throw new Error(datos.error ?? datos.message ?? 'No pude responder en este momento.');
            }
            return datos.respuesta;
        } catch (e) {
            if (e.name === 'AbortError') throw new Error('El asistente tardó demasiado. Intenta de nuevo.');
            throw e;
        } finally {
            clearTimeout(temporizador);
        }
    }

    // ---------- Componente ----------
    function iniciar(raiz) {
        const endpoint = raiz.dataset.endpoint;
        const ventana = raiz.querySelector('.campi__ventana');
        const lista = raiz.querySelector('[data-chatbot-mensajes]');
        const form = raiz.querySelector('[data-chatbot-form]');
        const input = form.querySelector('input');
        const boton = form.querySelector('button[type="submit"]');
        const sugerencias = raiz.querySelector('[data-chatbot-sugerencias]');
        const lanzador = raiz.querySelector('[data-chatbot-abrir]');
        const bienvenida = lista.innerHTML;
        let ocupado = false;

        function pintar(rol, texto, extraClase = '') {
            const burbuja = document.createElement('div');
            burbuja.className = `campi__burbuja campi__burbuja--${rol === 'user' ? 'usuario' : 'bot'} ${extraClase}`;
            burbuja.innerHTML = rol === 'user' ? escaparHTML(texto) : formatear(texto);
            lista.appendChild(burbuja);
            lista.scrollTop = lista.scrollHeight;
            return burbuja;
        }

        function repintarHistorial() {
            lista.innerHTML = bienvenida;
            const historial = almacen.leer();
            historial.forEach((m) => pintar(m.role, m.text));
            sugerencias.hidden = historial.length > 0;
        }

        async function preguntar(texto) {
            texto = texto.trim();
            if (!texto || ocupado) return;

            ocupado = true;
            boton.disabled = true;
            sugerencias.hidden = true;
            input.value = '';

            const historial = almacen.leer();
            pintar('user', texto);
            const escribiendo = pintar('model', 'Campi está escribiendo…', 'campi__burbuja--escribiendo');

            try {
                const respuesta = await enviarAlServidor(endpoint, texto, historial);
                escribiendo.remove();
                pintar('model', respuesta);
                almacen.guardar([...historial, { role: 'user', text: texto }, { role: 'model', text: respuesta }]);
            } catch (e) {
                escribiendo.remove();
                pintar('model', e.message, 'campi__burbuja--error');
            } finally {
                ocupado = false;
                boton.disabled = false;
                input.focus();
            }
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            preguntar(input.value);
        });

        sugerencias.addEventListener('click', (e) => {
            if (e.target.matches('.campi__chip')) preguntar(e.target.textContent);
        });

        raiz.querySelector('[data-chatbot-reiniciar]').addEventListener('click', () => {
            almacen.borrar();
            repintarHistorial();
            input.focus();
        });

        if (lanzador) {
            const alternar = (abrir) => {
                ventana.hidden = !abrir;
                lanzador.setAttribute('aria-expanded', String(abrir));
                raiz.classList.toggle('campi--abierto', abrir);
                if (abrir) { repintarHistorial(); input.focus(); }
            };
            lanzador.addEventListener('click', () => alternar(ventana.hidden));
            raiz.querySelector('[data-chatbot-cerrar]').addEventListener('click', () => alternar(false));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !ventana.hidden) alternar(false);
            });
        } else {
            repintarHistorial();
        }
    }

    document.querySelectorAll('[data-chatbot]').forEach(iniciar);
})();
