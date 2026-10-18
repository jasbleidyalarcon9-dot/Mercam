<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Post;
use App\Notifications\NuevoPedidoRecibido;
use App\Services\Cart;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct(private Cart $cart)
    {
    }

    public function index()
    {
        return view('carrito.index', [
            'lineas' => $this->cart->lineas(),
            'total' => $this->cart->total(),
        ]);
    }

    public function store(AddToCartRequest $request, Post $post)
    {
        if ($post->status !== 'published' || $post->stock < 1) {
            return back()->with('error', 'Ese producto no está disponible.');
        }

        $this->cart->agregar($post, $request->validated()['cantidad']);

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(AddToCartRequest $request, Post $post)
    {
        $this->cart->actualizarCantidad($post, $request->validated()['cantidad']);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function destroy(Post $post)
    {
        $this->cart->quitar($post);

        return back()->with('success', 'Producto quitado del carrito.');
    }

    /**
     * Checkout: valida stock real, crea el Pedido + sus renglones, y
     * descuenta el stock — todo dentro de una transacción con bloqueo de
     * filas para que dos compras al mismo tiempo no sobrevendan.
     */
    public function checkout()
    {
        $cantidadesSolicitadas = $this->cart->cantidadesSolicitadas();

        if (empty($cantidadesSolicitadas)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        try {
            $pedido = DB::transaction(function () use ($cantidadesSolicitadas) {
                $pedido = Pedido::create([
                    'user_id' => auth()->id(),
                    'total' => 0,
                ]);

                $total = 0;

                foreach ($cantidadesSolicitadas as $postId => $cantidad) {
                    // Bloqueamos la fila del producto para evitar que dos
                    // compradores agoten el mismo stock al mismo tiempo.
                    $post = Post::whereKey($postId)->lockForUpdate()->firstOrFail();

                    // se compara contra $cantidad,
                    // NUNCA contra una versión ya recortada al stock actual
                    // (eso dejaba pasar pedidos de cantidad 0 cuando el
                    // stock se agotaba entre que se agregó al carrito y el
                    // checkout, porque 0 >= 0 siempre "pasa").
                    if ($cantidad < 1 || !$post->hayStockPara($cantidad)) {
                        throw new \RuntimeException("No hay suficiente stock de \"{$post->title}\".");
                    }

                    $subtotal = round($post->precio * $cantidad, 2);

                    DetallePedido::create([
                        'pedido_id' => $pedido->id,
                        'post_id' => $post->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $post->precio,
                        'subtotal' => $subtotal,
                    ]);

                    $post->decrement('stock', $cantidad);
                    $total += $subtotal;
                }

                $pedido->update(['total' => $total]);

                return $pedido;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('carrito.index')->with('error', $e->getMessage());
        }

        $this->cart->vaciar();

        // Cada productor solo se entera de SUS propios renglones, nunca de
        // los productos de otro productor que hayan ido en el mismo pedido.
        $pedido->load('detalles.post.user');

        $pedido->detalles
            ->groupBy(fn (DetallePedido $d) => $d->post->user_id)
            ->each(function ($renglonesDelProductor) use ($pedido) {
                $productor = $renglonesDelProductor->first()->post->user;

                $resumen = $renglonesDelProductor->map(fn (DetallePedido $d) => [
                    'producto' => $d->post->title,
                    'cantidad' => $d->cantidad,
                    'subtotal' => $d->subtotal,
                ])->all();

                $productor->notify(new NuevoPedidoRecibido($pedido, $resumen));
            });

        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido realizado con éxito.');
    }
}
