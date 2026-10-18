<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\User;

class PedidoPolicy
{
    public function view(User $user, Pedido $pedido): bool
    {
        if ($pedido->user_id === $user->id) {
            return true; // es el comprador
        }

        // o es productor de al menos uno de los renglones del pedido
        return $pedido->detalles->contains(fn ($detalle) => $detalle->post->user_id === $user->id);
    }
}
