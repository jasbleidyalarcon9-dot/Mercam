<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
class PostPolicy

{
  
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Post $post)
    {

        return $post->user_id === $user->id;
    }
    public function delete(User $user, Post $post)
    {
        return $post->user_id === $user->id;
    }

    // public function restore(User $user, Post $post): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Post $post): bool
    // {
    //     return false;
    // }
}
