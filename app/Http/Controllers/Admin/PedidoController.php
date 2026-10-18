<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['comprador', 'detalles.post.user'])
            ->latest()
            ->paginate(20);

        return view('admin.pedidos', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $this->authorize('view', $pedido);

        $pedido->load('detalles.post.user', 'comprador');

        return view('pedidos.show', compact('pedido'));
    }
}