<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

/**
 * Carrito de compras basado en sesión. No necesita tabla en BD: cada
 * sesión de Laravel ya está aislada por usuario/navegador (session driver
 * de tu .env), así que un usuario jamás puede ver el carrito de otro.
 */
class Cart
{
    private const CLAVE_SESION = 'carrito';

    public function agregar(Post $post, int $cantidad): void
    {
        $carrito = $this->crudo();

        $actual = $carrito[$post->id] ?? 0;
        $carrito[$post->id] = min($actual + $cantidad, $post->stock);

        Session::put(self::CLAVE_SESION, $carrito);
    }

    public function actualizarCantidad(Post $post, int $cantidad): void
    {
        $carrito = $this->crudo();
        $carrito[$post->id] = min(max($cantidad, 1), $post->stock);

        Session::put(self::CLAVE_SESION, $carrito);
    }

    public function quitar(Post $post): void
    {
        $carrito = $this->crudo();
        unset($carrito[$post->id]);

        Session::put(self::CLAVE_SESION, $carrito);
    }

    public function vaciar(): void
    {
        Session::forget(self::CLAVE_SESION);
    }

    public function estaVacio(): bool
    {
        return count($this->crudo()) === 0;
    }

    /**
     * Cantidades tal como el usuario las pidió, SIN recortar contra el
     * stock actual. El checkout debe validar contra esto (no contra
     * lineas(), que ya viene recortada) para poder detectar y rechazar
     * el caso en que el stock se agotó entre que se agregó al carrito y
     * el momento de pagar.
     */
    public function cantidadesSolicitadas(): array
    {
        return $this->crudo();
    }

    /**
     * Devuelve una colección de líneas: cada una con 'post', 'cantidad' y
     * 'subtotal', ya recalculadas contra el precio y stock ACTUALES en BD
     * (nunca contra lo que hubiera guardado en sesión, por seguridad).
     */
    public function lineas(): Collection
    {
        $carrito = $this->crudo();

        if (empty($carrito)) {
            return collect();
        }

        return Post::whereIn('id', array_keys($carrito))
            ->get()
            ->map(function (Post $post) use ($carrito) {
                $cantidad = min($carrito[$post->id], $post->stock);

                return [
                    'post' => $post,
                    'cantidad' => $cantidad,
                    'subtotal' => round($post->precio * $cantidad, 2),
                ];
            });
    }

    public function total(): float
    {
        return round($this->lineas()->sum('subtotal'), 2);
    }

    private function crudo(): array
    {
        return Session::get(self::CLAVE_SESION, []);
    }
}
