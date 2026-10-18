@extends('layouts.app')

@section('content')
<div class="carrito-page">
    <div class="carrito-header">
        <span class="carrito-kicker">Mercado fresco</span>
        <h1>Tu carrito de frutas y verduras</h1>
        <p>Productos seleccionados del campo a tu cocina.</p>
    </div>

    @if ($lineas->isEmpty())
        <div class="carrito-empty">
            <div class="carrito-empty-icon">🥬</div>
            <h2>Tu carrito está vacío</h2>
            <p>Aún no has agregado frutas, verduras o productos frescos de Mercam.</p>
            <a href="{{ route('posts.index') }}" class="carrito-empty-btn">Explorar mercado</a>
        </div>
    @else
        <div class="carrito-layout">
            <div class="carrito-items">
                @foreach ($lineas as $linea)
                    @php
                        $imagenProducto = $linea['post']->imagen ?? '';
                        $imagenFinal = $imagenProducto;
                        if ($imagenProducto && !preg_match('/^https?:\/\//i', $imagenProducto)) {
                            $imagenFinal = Storage::url($imagenProducto);
                        }
                        if (!$imagenFinal) {
                            $imagenFinal = asset('img/Logomercam.png');
                        }
                    @endphp

                    <article class="carrito-item">
                        <div class="carrito-thumb" style="background-image: url('{{ $imagenFinal }}');"></div>

                        <div class="carrito-info">
                            <div class="carrito-meta">
                                <span class="carrito-category">{{ $linea['post']->categoria ?? 'Frutas y verduras' }}</span>
                                <span class="carrito-producer">{{ $linea['post']->productor ? $linea['post']->productor->name : 'Productor Mercam' }}</span>
                            </div>

                            <h2>{{ $linea['post']->title }}</h2>
                            <p>{{ $linea['post']->descripcion ?? 'Producto fresco y de temporada, seleccionado con cuidado.' }}</p>

                            <div class="carrito-actions">
                                <form action="{{ route('carrito.update', $linea['post']) }}" method="POST" class="carrito-update-form">
                                    @csrf
                                    @method('PUT')
                                    <label for="cantidad-{{ $linea['post']->id }}">Cantidad</label>
                                    <input id="cantidad-{{ $linea['post']->id }}" type="number" name="cantidad" min="1" max="{{ $linea['post']->stock }}" value="{{ $linea['cantidad'] }}">
                                    <button type="submit">Actualizar</button>
                                </form>

                                <form action="{{ route('carrito.destroy', $linea['post']) }}" method="POST" class="carrito-remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="carrito-remove-btn">Quitar</button>
                                </form>
                            </div>
                        </div>

                        <div class="carrito-price">
                            <strong>${{ number_format($linea['subtotal'], 0, ',', '.') }}</strong>
                            <small>${{ number_format($linea['post']->precio, 0, ',', '.') }} / {{ $linea['post']->unidad }}</small>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="carrito-summary">
                <div class="carrito-summary-header">
                    <span>Resumen</span>
                    <strong>{{ $lineas->count() }} productos</strong>
                </div>

                <div class="carrito-summary-row">
                    <span>Subtotal</span>
                    <strong>${{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                <div class="carrito-summary-row muted">
                    <span>Entrega</span>
                    <strong>Por confirmar</strong>
                </div>

                <div class="carrito-summary-total">
                    <span>Total estimado</span>
                    <strong>${{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                <form action="{{ route('carrito.checkout') }}" method="POST" class="carrito-checkout-form">
                    @csrf
                    <button type="submit">Confirmar pedido</button>
                </form>

                <p>El pago se coordina directamente con cada productor al momento de la entrega.</p>
            </aside>
        </div>
    @endif
</div>
@endsection
