<?php

namespace App\Policies;

use App\Models\DetallePedido;
use App\Models\User;

class DetallePedidoPolicy
{
    // el comprador y el productor de ese producto pueden ver el renglón
    public function view(User $user, DetallePedido $detalle): bool
    {
        return $detalle->pedido->user_id === $user->id
            || $detalle->post->user_id === $user->id;
    }

    // SOLO el productor dueño del producto puede cambiar el estado de su renglón
    public function update(User $user, DetallePedido $detalle): bool
    {
        return $detalle->post->user_id === $user->id;
    }

    // SOLO el comprador puede cancelar, y solo mientras siga "pendiente"
    // (una vez el productor lo confirma/envía, ya no se puede cancelar
    // unilateralmente desde el lado del comprador).
    public function cancelar(User $user, DetallePedido $detalle): bool
    {
        return $detalle->pedido->user_id === $user->id
            && $detalle->estado === 'pendiente';
    }
}
