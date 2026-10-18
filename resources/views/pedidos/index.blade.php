@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis pedidos</h1>

    @if ($pedidos->isEmpty())
        <p>Todavía no has hecho ningún pedido. <a href="{{ route('posts.index') }}">Ir al catálogo</a></p>
    @else
        <table class="posts-table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>#{{ $pedido->id }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $pedido->detalles->count() }}</td>
                        <td>${{ number_format($pedido->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn-view">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection
