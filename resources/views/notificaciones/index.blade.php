@extends('layouts.app')

@section('content')
<div class="notif-page">
    <div class="notif-header">
        <span class="notif-kicker">Centro de mensajes</span>

        <div class="notif-header-row">
            <h1>Notificaciones</h1>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notificaciones.marcarTodas') }}" method="POST" class="notif-mark-all-form">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="notif-btn notif-btn-muted">Marcar todas como leídas</button>
                </form>
            @endif
        </div>
    </div>

    @if ($notificaciones->isEmpty())
        <div class="notif-empty">
            <div class="notif-empty-icon">🔔</div>
            <h2>No tienes notificaciones</h2>
            <p>Cuando tengas movimientos relevantes, aparecerán aquí.</p>
        </div>
    @else
        <div class="notif-list">
            @foreach ($notificaciones as $n)
                <article class="notif-item {{ $n->read_at ? 'is-read' : 'is-unread' }}">
                    <div class="notif-dot" aria-hidden="true"></div>

                    <div class="notif-body">
                        <div class="notif-topline">
                            <span class="notif-badge">{{ $n->read_at ? 'Leída' : 'Nueva' }}</span>
                            <time>{{ $n->created_at->diffForHumans() }}</time>
                        </div>

                        <h2>{{ $n->data['titulo'] ?? 'Notificación' }}</h2>
                        <p>{{ $n->data['mensaje'] ?? '' }}</p>

                        <div class="notif-actions">
                            @if (isset($n->data['pedido_id']))
                                <a href="{{ route('pedidos.show', $n->data['pedido_id']) }}" class="notif-btn notif-btn-primary">Ver pedido</a>
                            @endif

                            @unless ($n->read_at)
                                <form action="{{ route('notificaciones.marcarLeida', $n->id) }}" method="POST" class="notif-inline-form">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="notif-btn notif-btn-secondary">Marcar leída</button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>
@endsection
