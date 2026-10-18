<?php

namespace App\Policies;

use App\Models\DetallePedido;
use App\Models\Post;
use App\Models\Resena;
use App\Models\User;

class ResenaPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Resena $resena): bool
    {
        return true;
    }

    public function create(User $user, Post $post): bool
    {
        // Solo quien de verdad compró y RECIBIÓ este producto puede
        // dejar reseña — evita reseñas de gente que nunca lo probó.
        return DetallePedido::where('post_id', $post->id)
            ->where('estado', 'entregado')
            ->whereHas('pedido', fn ($q) => $q->where('user_id', $user->id))
            ->exists();
    }

    public function update(User $user, Resena $resena): bool
    {
        return $resena->user_id === $user->id;
    }

    public function delete(User $user, Resena $resena): bool
    {
        return $resena->user_id === $user->id;
    }
}
