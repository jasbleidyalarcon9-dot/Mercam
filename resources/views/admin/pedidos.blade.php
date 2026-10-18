@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Todos los pedidos</h1>

    @if ($pedidos->isEmpty())
        <p>Todavía no hay pedidos en la plataforma.</p>
    @else
        <table class="posts-table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Comprador</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>#{{ $pedido->id }}</td>
                        <td>{{ $pedido->comprador->name }}</td>
                        <td style="font-size:13px;">
                            @foreach ($pedido->detalles as $detalle)
                                {{ $detalle->cantidad }}x {{ $detalle->post->title }}
                                (vende: {{ $detalle->post->user->name }}) &mdash; {{ ucfirst($detalle->estado) }}<br>
                            @endforeach
                        </td>
                        <td>${{ number_format($pedido->total, 0, ',', '.') }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn-view">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $pedidos->links() }}
        </div>
    @endif

    <a href="{{ route('admin.dashboard') }}">&larr; Volver al panel</a>
</div>
@endsection