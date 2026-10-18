@extends('layouts.app')

@section('content')
<div class="producer-dashboard">
    <div class="producer-topbar">
        <div>
            <span class="producer-kicker">Mercam productor</span>
            <h1>Panel del productor</h1>
        </div>
        <div class="producer-actions">
            <a href="{{ route('posts.mine') }}" class="producer-btn producer-btn-primary">Mis productos</a>
            <a href="{{ route('productor.pedidos') }}" class="producer-btn producer-btn-secondary">Pedidos recibidos</a>
        </div>
    </div>

    <div class="producer-stats">
        <div class="producer-stat producer-stat-highlight">
            <span>Productos publicados</span>
            <strong>{{ $stats['productos_publicados'] }}</strong>
            <small>activos en catálogo</small>
        </div>

        <div class="producer-stat">
            <span>Sin stock</span>
            <strong>{{ $stats['productos_sin_stock'] }}</strong>
            <small>requieren atención</small>
        </div>

        <div class="producer-stat">
            <span>Pedidos por atender</span>
            <strong>{{ $stats['pedidos_pendientes'] }}</strong>
            <small>nuevos pedidos</small>
        </div>

        <div class="producer-stat">
            <span>Ingresos confirmados</span>
            <strong>${{ number_format($stats['ingresos_confirmados'], 0, ',', '.') }}</strong>
            <small>ventas cerradas</small>
        </div>
    </div>

    <div class="producer-summary">
        <div class="producer-summary-header">
            <span>Resumen</span>
        </div>

        <div class="producer-summary-grid">
            <div class="producer-summary-card">
                <h3>Tu catálogo</h3>
                <p>Gestiona tus productos y mantén tu inventario actualizado para vender más rápido.</p>
                <a href="{{ route('posts.mine') }}" class="producer-link">Ver mis productos</a>
            </div>

            <div class="producer-summary-card">
                <h3>Pedidos</h3>
                <p>Revisa el estado de tus compras y responde a cada pedido con rapidez y claridad.</p>
                <a href="{{ route('productor.pedidos') }}" class="producer-link">Ver pedidos</a>
            </div>
        </div>
    </div>
</div>
@endsection
