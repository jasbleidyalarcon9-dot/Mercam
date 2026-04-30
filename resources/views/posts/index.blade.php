@extends('layouts.app')

@section('content')

<div class="container">

    <h1>Gestión de publicaciones</h1>

    <a href="{{ route('posts.create') }}"> class="btn btn"
        Crear publicación
    </a>

    @if(session('ok'))
        <p>{{ session('ok') }}</p>
    @endif

    @foreach($posts as $post)
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->title }}</td> {{-- XSS protegido --}}
            <td>{{ $post->status }}</td>
            <td>
                <a href="{{ route('posts.edit',$post->id) }}">Editar</a>

                <form method="POST"
                      action="{{ route('posts.destroy',$post->id) }}"
                      style="display:inline">

                    @csrf
                    @method('DELETE')

                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    @endforeach
</div>
@endsection