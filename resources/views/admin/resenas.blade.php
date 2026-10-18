@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Todas las reseñas</h1>

    @if ($resenas->isEmpty())
        <p>Todavía no hay reseñas en la plataforma.</p>
    @else
        @foreach ($resenas as $resena)
            <div class="resena-item">
                <div class="resena-contenido">
                    <strong>{{ $resena->user->name }}</strong>
                    @if ($resena->calificacion)
                        <span>&mdash; {{ $resena->calificacion }}/5</span>
                    @endif
                    <p style="font-size:13px; color:#666;">
                        sobre <strong>{{ $resena->post->title }}</strong> (de {{ $resena->post->user->name }})
                        &mdash; {{ $resena->created_at->format('d/m/Y') }}
                    </p>
                    <p>{{ $resena->comentario }}</p>

                    <form action="{{ route('admin.resenas.destroy', $resena) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar esta reseña de forma permanente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" style="width:auto; padding:4px 10px;">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach

        <div class="pagination">
            {{ $resenas->links() }}
        </div>
    @endif

    <a href="{{ route('admin.dashboard') }}">&larr; Volver al panel</a>
</div>
@endsection