@extends('layouts.app')

@section('content')
<div class="orders-panel">
    <div class="orders-header">
        <div>
            <span class="orders-kicker">Mercam productor</span>
            <h1>Pedidos recibidos</h1>
        </div>
        <a href="{{ route('productor.dashboard') }}" class="orders-back">&larr; Volver al panel</a>
    </div>

    @if ($renglones->isEmpty())
        <div class="orders-empty">
            <div class="orders-empty-icon">✓</div>
            <h3>Todavía no has recibido pedidos.</h3>
            <p>Cuando lleguen nuevas ventas, aparecerán aquí para que puedas gestionarlas.</p>
        </div>
    @else
        <div class="orders-summary">
            <div class="orders-summary-item">
                <span>Total</span>
                <strong>{{ $renglones->total() }}</strong>
            </div>
            <div class="orders-summary-item">
                <span>Por atender</span>
                <strong>{{ $renglones->where('estado', 'pendiente')->count() }}</strong>
            </div>
            <div class="orders-summary-item">
                <span>Confirmados</span>
                <strong>{{ $renglones->where('estado', 'confirmado')->count() }}</strong>
            </div>
        </div>

        <div class="orders-list">
            @foreach ($renglones as $detalle)
                <article class="order-card">
                    <div class="order-card-top">
                        <div>
                            <span class="order-id">Pedido #{{ $detalle->pedido_id }}</span>
                            <h3>{{ $detalle->post->title }}</h3>
                        </div>
                        <span class="order-status order-status-{{ $detalle->estado ?? 'pendiente' }}">
                            {{ ucfirst($detalle->estado ?? 'pendiente') }}
                        </span>
                    </div>

                    <div class="order-meta">
                        <div>
                            <label>Comprador</label>
                            <strong>{{ $detalle->pedido->comprador->name }}</strong>
                        </div>
                        <div>
                            <label>Cantidad</label>
                            <strong>{{ $detalle->cantidad }}</strong>
                        </div>
                        <div>
                            <label>Subtotal</label>
                            <strong>${{ number_format($detalle->subtotal, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('productor.pedidos.estado', $detalle) }}" method="POST" class="order-form">
                        @csrf
                        @method('PUT')
                        <label for="estado-{{ $detalle->id }}">Actualizar estado</label>
                        <div class="order-form-actions">
                            <select id="estado-{{ $detalle->id }}" name="estado">
                                @foreach (\App\Models\DetallePedido::ESTADOS as $estado)
                                    <option value="{{ $estado }}" {{ $detalle->estado === $estado ? 'selected' : '' }}>
                                        {{ ucfirst($estado) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="order-save-btn">Guardar</button>
                        </div>
                    </form>
                </article>
            @endforeach
        </div>

        <div class="orders-pagination">
            {{ $renglones->links() }}
        </div>
    @endif
</div>
@endsection
