<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Notifications\RenglonCanceladoPorComprador;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // "Mis pedidos": SOLO los del usuario autenticado como comprador
    public function index()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->with('detalles.post')
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $this->authorize('view', $pedido);

        $pedido->load('detalles.post.user', 'comprador');

        return view('pedidos.show', compact('pedido'));
    }

    // El comprador cancela SU PROPIO renglón, y solo mientras siga
    // "pendiente" (DetallePedidoPolicy::cancelar). Devuelve el stock.
    public function cancelar(DetallePedido $detalle)
    {
        $this->authorize('cancelar', $detalle);

        DB::transaction(function () use ($detalle) {
            $detalle->post()->increment('stock', $detalle->cantidad);
            $detalle->update(['estado' => 'cancelado']);
        });

        $detalle->load('post.user');
        $detalle->post->user->notify(new RenglonCanceladoPorComprador($detalle));

        return back()->with('success', 'Cancelaste ese producto de tu pedido.');
    }
}
