<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResenaRequest;
use App\Http\Requests\UpdateResenaRequest;
use App\Models\Post;
use App\Models\Resena;

class ResenaController extends Controller
{
    // Crea una reseña sobre una publicación puntual: POST /posts/{post}/resenas
    public function store(StoreResenaRequest $request, Post $post)
    {
        $this->authorize('create', [Resena::class, $post]);

        $data = $request->validated();

        // user_id sale SIEMPRE del usuario autenticado, nunca del formulario.
        $post->resenas()->create([
            ...$data,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Reseña enviada correctamente');
    }

    public function update(UpdateResenaRequest $request, Resena $resena)
    {
        $this->authorize('update', $resena);

        $resena->update($request->validated());

        return back()->with('success', 'Reseña actualizada.');
    }

    public function destroy(Resena $resena)
    {
        $this->authorize('delete', $resena);

        $resena->delete();

        return back()->with('success', 'Reseña eliminada.');
    }
}
