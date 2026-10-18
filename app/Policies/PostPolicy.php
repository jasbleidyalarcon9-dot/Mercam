<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    // $user es nullable: un visitante sin sesión puede ver productos publicados
    public function view(?User $user, Post $post): bool
    {
        if ($post->status === 'published') {
            return true;
        }

        // Un borrador SOLO lo puede ver su dueño.
        return $user !== null && $post->user_id === $user->id;
    }

    // Solo productores (y administradores) publican productos en el catálogo
    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['productor', 'administrador'], true);
    }

    public function update(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }
}
