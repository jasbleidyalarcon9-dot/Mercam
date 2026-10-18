@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pedido #{{ $pedido->id }}</h1>
    <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Comprador:</strong> {{ $pedido->comprador->name }}</p>

    <table class="posts-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Productor</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->post->title }}</td>
                    <td>{{ $detalle->post->user->name }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                    <td>${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($detalle->estado) }}</td>
                    <td>
                        @can('cancelar', $detalle)
                            <form action="{{ route('pedidos.cancelar', $detalle) }}" method="POST"
                                  onsubmit="return confirm('¿Cancelar este producto de tu pedido? Se libera el stock.');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-delete" style="width:auto; padding:6px 10px;">Cancelar</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align:right; margin-top:20px;">
        <h3>Total: ${{ number_format($pedido->total, 0, ',', '.') }}</h3>
        <p style="font-size:13px; color:#666;">
            Este pedido no se paga en línea: coordina el pago directamente con cada
            productor al momento de la entrega (contraentrega o acuerdo directo).
        </p>
    </div>

    @if (auth()->user()->isAdmin() && auth()->id() !== $pedido->user_id)
    <a href="{{ route('admin.pedidos.index') }}">&larr; Volver a todos los pedidos</a>
@else
    <a href="{{ route('pedidos.index') }}">&larr; Volver a mis pedidos</a>
@endif
</div>
@endsection
