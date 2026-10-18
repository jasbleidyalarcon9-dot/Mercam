@extends('layouts.app')

@section('title', 'Mercam — Del cultivo a su hogar')

@section('content')
<div class="campo-home">
    <div id="campoHeroCarousel" class="carousel slide campo-hero-image" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#campoHeroCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#campoHeroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#campoHeroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/verduras cosechas.jpg') }}" alt="Cosecha fresca de productores locales">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/mazorca cultivo.jpg') }}" alt="Cultivo de maíz en el campo">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/zanahoria cultivo.jpeg') }}" alt="Cultivo de zanahoria en el campo">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#campoHeroCarousel" data-bs-slide="prev" aria-label="Anterior">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#campoHeroCarousel" data-bs-slide="next" aria-label="Siguiente">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>

        <div class="campo-harvest-note"><div><b>Cosecha local</b><span>Frescos, de temporada y cerca de ti</span></div></div>

        <a href="{{ route('manual.uso') }}" class="campo-help-badge" aria-label="Manual de uso">
            <span class="campo-help-circle">?</span>
            <span class="campo-help-label">Manual de uso</span>
        </a>
    </div>

    <section class="campo-hero">
        <div class="campo-hero-copy">
            <span class="campo-kicker">Mercados campesinos directos</span>
            <h1>Del cultivo a su hogar, más cerca que nunca.</h1>
            <div class="campo-hero-actions">
                <a href="#productos" class="campo-button campo-button-primary">Explorar productos <span aria-hidden="true">&rarr;</span></a>
                <a href="#categorias" class="campo-button campo-button-ghost">Conoce las categorías</a>
            </div>
        </div>
    </section>

    <section class="campo-section campo-category-section" id="categorias">
        <div class="campo-section-heading"><div><span class="campo-eyebrow">Explora lo que necesitas</span><h2>Categorías</h2></div><a href="#productos">Ver todos <span aria-hidden="true">&rarr;</span></a></div>
        <div class="campo-categories">
            @forelse ($categorias as $categoria)
                <a href="{{ route('posts.index', ['categoria' => $categoria['slug']]) }}" class="campo-category-card">
                    <span class="campo-category-cover" style="background-image: url('{{ $categoria['imagen'] }}');" aria-hidden="true"></span>
                    <span class="campo-category-content">
                        <strong>{{ $categoria['nombre'] }}</strong>
                        <small>Ver productos <span aria-hidden="true">&rarr;</span></small>
                    </span>
                </a>
            @empty
                <p class="campo-muted">Aún no hay categorías disponibles.</p>
            @endforelse
        </div>
    </section>

    <section class="campo-section campo-products-section" id="productos">
        <div class="campo-section-heading"><div><span class="campo-eyebrow">De productores a tu hogar</span><h2>Productos frescos</h2></div><form method="GET" action="{{ route('posts.index') }}" class="campo-search-form"><label class="visually-hidden" for="buscar">Buscar productos</label><input id="buscar" type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Busca un producto..."><button type="submit" aria-label="Buscar">⌕</button></form></div>
        @if ($posts->isEmpty())
            <div class="campo-empty"><span aria-hidden="true">🌱</span><h3>No encontramos productos</h3><p>Prueba con otra búsqueda o revisa todas las categorías.</p><a href="{{ route('posts.index') }}" class="campo-button campo-button-primary">Ver todo el catálogo</a></div>
        @else
            <div class="campo-products-grid">
                @foreach ($posts as $post)
                    <article class="campo-product-card">
                        <a href="{{ route('posts.show', $post) }}" class="campo-product-image">
                            @if ($post->imagen)
                                @php
                                    $imagenProducto = preg_match('/^https?:\/\//i', $post->imagen)
                                        ? $post->imagen
                                        : Storage::url($post->imagen);
                                @endphp
                                <img src="{{ $imagenProducto }}" alt="{{ $post->title }}">
                            @else
                                <img src="{{ asset('img/Logomercam.png') }}" alt="{{ $post->title }}">
                            @endif
                            @if ($post->stock < 1)<span class="campo-sold-out">Agotado</span>@endif
                        </a>
                        <div class="campo-product-content"><span class="campo-product-category">{{ $post->categoria ? ucfirst($post->categoria) : 'Producto local' }}</span><h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3><p class="campo-producer">Por {{ $post->user->name }}</p><div class="campo-product-footer"><strong>${{ number_format($post->precio, 0, ',', '.') }} <small>/ {{ $post->unidad }}</small></strong><a href="{{ route('posts.show', $post) }}" class="campo-icon-button" aria-label="Ver {{ $post->title }}" title="Ver producto">&rarr;</a></div></div>
                    </article>
                @endforeach
            </div>
            <div class="campo-pagination">{{ $posts->links() }}</div>
        @endif
    </section>

    <section class="campo-producer-banner"><div><span class="campo-eyebrow">Para productores</span><h2>Si es productor agrícola, registre sus productos y amplíe sus oportunidades de venta.<br><em>Vendé acá.</em></h2><a href="{{ route('register') }}" class="campo-button campo-button-light">Registrate <span aria-hidden="true">&rarr;</span></a></div><div class="campo-banner-shape" aria-hidden="true"></div></section>

    <section class="campo-benefits">
        <div><img class="campo-benefit-icon" src="{{ asset('img/directo-campo-carrito.svg') }}" alt="Directo del campo"><strong>Directo del campo</strong><small>Sin intermediarios</small></div>
        <div><img class="campo-benefit-icon" src="{{ asset('img/candado-seguro.svg') }}" alt="Pago seguro"><strong>Pago seguro</strong><small>Transacciones protegidas</small></div>
        <div><img class="campo-benefit-icon" src="{{ asset('img/entrega-domicilio.svg') }}" alt="Entrega a domicilio"><strong>Entrega a domicilio</strong><small>En tu ciudad o mercado</small></div>
        <div><img class="campo-benefit-icon" src="{{ asset('img/comercio-justo-check.svg') }}" alt="Comercio justo"><strong>Comercio justo</strong><small>Precio justo para el productor</small></div>
    </section>
</div>
@endsection
