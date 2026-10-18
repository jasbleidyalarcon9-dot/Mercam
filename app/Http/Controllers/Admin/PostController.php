<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // A diferencia del catálogo público o "mis productos", aquí el
        // admin ve TODO: publicados y borradores, de cualquier productor.
        $posts = Post::with('user')->latest()->paginate(20);

        return view('admin.productos', compact('posts'));
    }

    public function despublicar(Post $post)
    {
        $this->authorize('update', $post); // el Gate::before deja pasar al admin

        $post->update(['status' => 'draft']);

        return back()->with('success', "\"{$post->title}\" fue despublicado.");
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // el Gate::before deja pasar al admin

        if ($post->detallePedidos()->exists()) {
            return back()->with(
                'error',
                'No puedes eliminar este producto porque ya tiene pedidos asociados '
                .'(se perdería el historial de compra). Despublícalo en su lugar.'
            );
        }

        if ($post->imagen) {
            Storage::disk('public')->delete($post->imagen);
        }

        $post->delete();

        return back()->with('success', "\"{$post->title}\" fue eliminado.");
    }
}
