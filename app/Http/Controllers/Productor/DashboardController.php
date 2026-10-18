<?php

namespace App\Http\Controllers\Productor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEstadoDetalleRequest;
use App\Models\DetallePedido;
use App\Models\Post;
use App\Notifications\EstadoPedidoActualizado;

class DashboardController extends Controller
{
    // Panel principal del productor: solo ve NÚMEROS de lo suyo.
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'productos_publicados' => Post::where('user_id', $userId)->where('status', 'published')->count(),
            'productos_sin_stock' => Post::where('user_id', $userId)->where('stock', 0)->count(),
            'pedidos_pendientes' => DetallePedido::whereHas('post', fn ($q) => $q->where('user_id', $userId))
                ->where('estado', 'pendiente')->count(),
            'ingresos_confirmados' => DetallePedido::whereHas('post', fn ($q) => $q->where('user_id', $userId))
                ->whereIn('estado', ['confirmado', 'enviado', 'entregado'])
                ->sum('subtotal'),
        ];

        return view('productor.dashboard', compact('stats'));
    }

    // Pedidos recibidos: SOLO los renglones donde el producto es del productor autenticado
    public function pedidos()
    {
        $renglones = DetallePedido::whereHas('post', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['post', 'pedido.comprador'])
            ->latest()
            ->paginate(15);

        return view('productor.pedidos', compact('renglones'));
    }

    public function actualizarEstado(UpdateEstadoDetalleRequest $request, DetallePedido $detalle)
    {
        $this->authorize('update', $detalle);

        $detalle->update($request->validated());

        $detalle->load('pedido.comprador', 'post');
        $detalle->pedido->comprador->notify(new EstadoPedidoActualizado($detalle));

        return back()->with('success', 'Estado actualizado.');
    }
}
