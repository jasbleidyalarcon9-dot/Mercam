@extends('layouts.app')

@section('content')
<div class="products-page">
    <div class="products-header">
        <span class="products-kicker">Panel del productor</span>
        <div class="products-header-row">
            <h1>Mis productos</h1>
            <a href="{{ route('posts.create') }}" class="products-btn products-btn-primary">+ Publicar producto</a>
        </div>
    </div>

    @if (session('success'))
        <div class="success-message products-success">{{ session('success') }}</div>
    @endif

    <div class="products-dashboard">
        <div class="products-stats">
            <div class="stat-card stat-card-primary">
                <span>Total</span>
                <strong>{{ $stats['total'] }}</strong>
                <small>productos</small>
            </div>
            <div class="stat-card">
                <span>Publicados</span>
                <strong>{{ $stats['publicados'] }}</strong>
                <small>activos</small>
            </div>
            <div class="stat-card">
                <span>Borradores</span>
                <strong>{{ $stats['borradores'] }}</strong>
                <small>en edición</small>
            </div>
            <div class="stat-card">
                <span>Inventario</span>
                <strong>${{ number_format($stats['valor'], 0, ',', '.') }}</strong>
                <small>valor estimado</small>
            </div>
        </div>

        @if ($posts->isEmpty())
            <div class="products-empty">
                <div class="products-empty-icon">🌿</div>
                <h2>Aún no has publicado productos</h2>
                <p>Empieza a vender tu cosecha y comparte tus productos frescos con la comunidad.</p>
                <a href="{{ route('posts.create') }}" class="products-btn products-btn-primary">Crear mi primer producto</a>
            </div>
        @else
            <div class="products-panel">
                <div class="products-panel-header">
                    <div>
                        <span class="panel-label">Inventario</span>
                        <h2>Productos publicados</h2>
                    </div>
                    <a href="{{ route('posts.create') }}" class="products-btn products-btn-secondary">Nuevo producto</a>
                </div>

                <div class="products-grid">
                    @foreach ($posts as $post)
                        <article class="product-card">
                            <div class="product-card-image">
                                @if ($post->imagen)
                                    <img src="{{ Storage::url($post->imagen) }}" alt="{{ $post->title }}">
                                @else
                                    <div class="product-card-fallback">🌾</div>
                                @endif
                            </div>

                            <div class="product-card-body">
                                <div class="product-card-topline">
                                    <span class="product-status {{ $post->status === 'published' ? 'published' : 'draft' }}">
                                        {{ $post->status === 'published' ? 'Publicado' : 'Borrador' }}
                                    </span>
                                    <span class="product-stock">Stock: {{ $post->stock }}</span>
                                </div>

                                <h2>{{ $post->title }}</h2>

                                <div class="product-price-row">
                                    <strong>${{ number_format($post->precio, 0, ',', '.') }}</strong>
                                    <span>/ {{ $post->unidad }}</span>
                                </div>

                                <div class="product-actions">
                                    <a href="{{ route('posts.show', $post) }}" class="products-btn products-btn-ghost">Ver</a>
                                    <a href="{{ route('posts.edit', $post) }}" class="products-btn products-btn-secondary">Editar</a>

                                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este producto? Esta acción no se puede deshacer.');" class="product-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="products-btn products-btn-delete">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="pagination">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <div class="products-footer">
        <a href="{{ route('productor.dashboard') }}">&larr; Volver al panel del productor</a>
    </div>
</div>
@endsection
