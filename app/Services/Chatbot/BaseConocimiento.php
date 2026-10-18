<?php

namespace App\Services\Chatbot;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Base de conocimiento del asistente (RAG ligero).
 *
 * Combina dos fuentes:
 *  1. Conocimiento FIJO: resources/chatbot/conocimiento.md (qué es Mercam,
 *     manual de uso, roles, contacto). Se edita sin tocar código.
 *  2. Conocimiento VIVO: el catálogo real de la base de datos (solo productos
 *     PUBLICADOS, nunca borradores ni datos personales). Se hace una
 *     recuperación por palabras clave sobre la pregunta del usuario y solo se
 *     envían al modelo los productos más relevantes.
 */
class BaseConocimiento
{
    /** Máximo de productos que se envían al modelo en cada pregunta. */
    private const MAX_PRODUCTOS = 15;

    /** Palabras vacías que no sirven para buscar. */
    private const STOPWORDS = [
        'a', 'al', 'algo', 'alguna', 'alguno', 'cual', 'cuales', 'cuanto', 'cuanta', 'cuantos', 'cuesta', 'cuestan',
        'de', 'del', 'el', 'en', 'es', 'esta', 'estan', 'hay', 'la', 'las', 'lo', 'los', 'me', 'mi', 'para',
        'por', 'precio', 'precios', 'que', 'quien', 'se', 'si', 'su', 'sus', 'tiene', 'tienen', 'tienes', 'un',
        'una', 'unos', 'vende', 'venden', 'y', 'yo', 'como', 'donde', 'quiero', 'comprar', 'producto', 'productos',
        'the', 'is', 'how', 'much', 'what', 'do', 'you', 'have', 'price',
    ];

    public function fija(): string
    {
        $ruta = resource_path('chatbot/conocimiento.md');

        return is_file($ruta) ? trim(file_get_contents($ruta)) : '';
    }

    /**
     * Texto con el catálogo relevante para la pregunta.
     */
    public function catalogoPara(string $pregunta): string
    {
        $disponibles = Post::publicados()->with('user:id,name')->latest()->get();

        if ($disponibles->isEmpty()) {
            return "## CATÁLOGO ACTUAL\nEn este momento no hay productos publicados.";
        }

        $relevantes = $this->recuperar($disponibles, $pregunta);

        $lineas = $relevantes->map(fn (Post $p) => $this->describir($p))->implode("\n");

        $resumen = $disponibles->groupBy(fn (Post $p) => $p->categoria ?: 'sin categoría')
            ->map(fn (Collection $g, string $cat) => "{$cat}: {$g->count()}")
            ->implode(', ');

        return "## CATÁLOGO ACTUAL (datos reales de la base de datos)\n"
            ."Total de productos publicados: {$disponibles->count()} ({$resumen}).\n"
            ."Productos más relevantes para esta pregunta:\n{$lineas}";
    }

    /**
     * Recuperación por palabras clave: puntúa cada producto según cuántos
     * términos de la pregunta aparecen en su título, categoría, descripción
     * o nombre del productor. Si nada coincide, devuelve los más recientes.
     */
    private function recuperar(Collection $productos, string $pregunta): Collection
    {
        $terminos = $this->terminos($pregunta);

        if ($terminos->isEmpty()) {
            return $productos->take(self::MAX_PRODUCTOS);
        }

        $puntuados = $productos->map(function (Post $p) use ($terminos) {
            $titulo = $this->normalizar($p->title.' '.$p->categoria);
            $resto = $this->normalizar($p->content.' '.$p->user?->name);

            $puntos = $terminos->sum(function (string $t) use ($titulo, $resto) {
                // Coincidencia por raíz para tolerar plurales: "papas" ~ "papa"
                $raiz = Str::length($t) > 4 ? Str::substr($t, 0, -1) : $t;

                return (str_contains($titulo, $raiz) ? 3 : 0) + (str_contains($resto, $raiz) ? 1 : 0);
            });

            return ['post' => $p, 'puntos' => $puntos];
        })->filter(fn ($x) => $x['puntos'] > 0)
            ->sortByDesc('puntos')
            ->pluck('post');

        return $puntuados->isNotEmpty()
            ? $puntuados->take(self::MAX_PRODUCTOS)
            : $productos->take(self::MAX_PRODUCTOS);
    }

    private function terminos(string $texto): Collection
    {
        return collect(preg_split('/[^a-z0-9ñ]+/u', $this->normalizar($texto)))
            ->filter(fn ($t) => Str::length($t) >= 3 && ! in_array($t, self::STOPWORDS, true))
            ->unique()
            ->values();
    }

    private function normalizar(?string $texto): string
    {
        $texto = Str::lower((string) $texto);

        return strtr($texto, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u']);
    }

    private function describir(Post $p): string
    {
        $precio = '$'.number_format((float) $p->precio, 0, ',', '.').' COP';
        $stock = $p->stock > 0 ? "{$p->stock} disponibles" : 'AGOTADO';
        $descripcion = Str::limit(strip_tags((string) $p->content), 160);
        $enlace = route('posts.show', $p->slug);

        return "- {$p->title} | categoría: ".($p->categoria ?: 'sin categoría')
            ." | precio: {$precio} por {$p->unidad} | stock: {$stock}"
            .' | productor: '.($p->user?->name ?? 'productor de Mercam')
            ." | descripción: {$descripcion} | enlace: {$enlace}";
    }
}
