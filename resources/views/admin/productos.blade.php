@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Moderar productos</h1>

    <table class="posts-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Productor</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->user->name }}</td>
                    <td>{{ $post->status === 'published' ? 'Publicado' : 'Borrador' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('posts.show', $post) }}" class="btn-view">Ver</a>

                            @if ($post->status === 'published')
                                <form action="{{ route('admin.productos.despublicar', $post) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-edit">Despublicar</button>
                                </form>
                            @endif

                            <form action="{{ route('admin.productos.destroy', $post) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este producto de forma permanente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $posts->links() }}
    </div>

    <a href="{{ route('admin.dashboard') }}">&larr; Volver al panel</a>
</div>
@endsection
