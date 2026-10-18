@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-edit-post">
        <h1>Detalle del producto</h1>

        @if ($post->imagen)
            <img src="{{ Storage::url($post->imagen) }}" alt="{{ $post->title }}"
                 style="width:100%; max-height:320px; object-fit:cover; border-radius:12px; margin-bottom:20px;">
        @endif

        <h2>{{ $post->title }}</h2>
        <p>{{ $post->content }}</p>

        <p><strong>Precio:</strong> ${{ number_format($post->precio, 0, ',', '.') }} / {{ $post->unidad }}</p>
        <p><strong>Disponibilidad:</strong> {{ $post->stock > 0 ? $post->stock.' '.$post->unidad.'(s)' : 'Agotado' }}</p>
        @if ($post->categoria)
            <p><strong>Categoría:</strong> {{ $post->categoria }}</p>
        @endif
        <p><strong>Vendido por:</strong> {{ $post->user->name }}</p>
        @if ($post->enlace)
            <p><strong>Más información:</strong> <a href="{{ $post->enlace }}" target="_blank" rel="noopener">{{ $post->enlace }}</a></p>
        @endif

        {{-- Agregar al carrito: solo tiene sentido para compradores logueados,
             y solo si el productor no está viendo su propio producto. --}}
        @auth
            @if (auth()->id() !== $post->user_id)
                @if ($post->status === 'published' && $post->stock > 0)
                    <form action="{{ route('carrito.store', $post) }}" method="POST" class="d-flex align-items-center" style="gap:10px; margin-top:20px;">
                        @csrf
                        <label for="cantidad" style="margin:0;">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" min="1" max="{{ $post->stock }}" value="1"
                               style="width:80px; padding:6px; border-radius:6px; border:1px solid #ccc;">
                        <button type="submit" class="btn-create" style="border:none;">Agregar al carrito</button>
                    </form>
                @else
                    <p style="margin-top:20px; color:#c0392b;"><strong>Producto no disponible por ahora.</strong></p>
                @endif
            @endif
        @else
            <p style="margin-top:20px;">
                <a href="{{ route('login') }}">Inicia sesión</a> para poder comprar este producto.
            </p>
        @endauth
    </div>

    {{-- Reseñas --}}
    <div class="resenas-container" style="margin-top:30px;">
        <div class="resenas-lista">
            <h3>Reseñas ({{ $post->resenas->count() }})</h3>

            @forelse ($post->resenas as $resena)
                <div class="resena-item">
                    <div class="resena-contenido">
                        <strong>{{ $resena->user->name }}</strong>
                        @if ($resena->calificacion)
                            <span>&mdash; {{ $resena->calificacion }}/5</span>
                        @endif
                        <p>{{ $resena->comentario }}</p>

                        @auth
                            @if (auth()->id() === $resena->user_id)
                                <form action="{{ route('resenas.destroy', $resena) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar tu reseña?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" style="width:auto; padding:4px 10px;">Eliminar</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <p>Todavía no hay reseñas para este producto.</p>
            @endforelse
        </div>

        @auth
            @if (auth()->id() !== $post->user_id)
                <div class="resenas-form">
                    <h3>Dejar una reseña</h3>

                    @can('create', [App\Models\Resena::class, $post])
                        @if ($errors->any())
                            <div class="success-message" style="background:#c0392b;">
                                <ul style="margin:0; padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('resenas.store', $post) }}" method="POST">
                            @csrf

                            <select name="calificacion" style="padding:8px; border-radius:8px; border:1px solid #ccd0d5;">
                                <option value="">Sin calificación</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} / 5</option>
                                @endfor
                            </select>

                            <textarea name="comentario" placeholder="Cuéntanos tu experiencia..." required minlength="5" maxlength="255"></textarea>

                            <button type="submit" class="btn-enviar">Enviar reseña</button>
                        </form>
                    @else
                        <p style="color:#666; font-size:14px;">
                            Solo puedes dejar una reseña de productos que hayas comprado y recibido.
                        </p>
                    @endcan
                </div>
            @endif
        @endauth
    </div>

    <a href="{{ route('posts.index') }}" style="display:inline-block; margin-top:20px;">&larr; Volver al catálogo</a>
</div>
@endsection
