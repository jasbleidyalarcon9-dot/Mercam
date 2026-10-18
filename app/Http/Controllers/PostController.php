<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Catálogo público: solo productos publicados. Cualquier visitante,
     * con o sin sesión, puede entrar aquí. Admite filtros ?categoria= y ?buscar=
     */
    public function index()
    {
        $posts = Post::publicados()
            ->with('user')
            ->deCategoria(request('categoria'))
            ->when(request('buscar'), fn ($q, $buscar) => $q->where('title', 'like', "%{$buscar}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categorias = collect([
            [
                'slug' => 'frutas',
                'nombre' => 'Frutas',
                'imagen' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'verduras',
                'nombre' => 'Verduras',
                'imagen' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'lacteos',
                'nombre' => 'Lácteos',
                'imagen' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'granos y semillas',
                'nombre' => 'Granos y semillas',
                'imagen' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'slug' => 'papa',
                'nombre' => 'Papa',
                'imagen' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=1200&q=80',
            ],
        ]);

        return view('posts.index', compact('posts', 'categorias'));
    }

    /**
     * Panel privado del productor: SOLO sus propios productos, incluidos
     * borradores. Nadie más puede ver este listado (ver PostPolicy).
     */
    public function mine()
    {
        $allPostsQuery = auth()->user()->posts();
        $posts = $allPostsQuery->latest()->paginate(8);
        $allPosts = $allPostsQuery->get();

        $stats = [
            'total' => $allPosts->count(),
            'publicados' => $allPosts->where('status', 'published')->count(),
            'borradores' => $allPosts->where('status', '!=', 'published')->count(),
            'valor' => $allPosts->sum(fn ($post) => (float) $post->precio * (int) $post->stock),
        ];

        return view('posts.mine', compact('posts', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        }

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Se crea siempre colgado del usuario autenticado: nadie puede
        // publicar a nombre de otro usuario (no se acepta user_id del form).
        auth()->user()->posts()->create($data);

        return redirect()->route('posts.mine')->with('success', 'Producto creado.');
    }

    public function show(Post $post) // Route Model Binding
    {
        $this->authorize('view', $post);

        $post->load(['user', 'resenas.user']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        }

        if ($request->hasFile('imagen')) {
            if ($post->imagen) {
                Storage::disk('public')->delete($post->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.mine')->with('success', 'Producto actualizado.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

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

        return redirect()->route('posts.mine')->with('success', 'Producto eliminado.');
    }
}
