@extends('layouts.app')

@section('content')

<div class="container">

    <h1>Gestión de publicaciones</h1>

    <br>
<a href="{{ route('dashboard') }}" class="btn btn-secondary" style="margin-right: 15px;">
    Volver
</a>
    <a href="{{ route('posts.create') }}" class="btn btn-primary">
        Crear publicación
    </a>

    <br><br>

    @if(session('ok'))
        <p>{{ session('ok') }}</p>
    @endif

    <table border="1" style="border-collapse: collapse; width: 100%; text-align: center;">
        <thead>
            <tr>
                <th style="padding: 15px;">ID</th>
                <th style="padding: 15px;">Título</th>
                <th style="padding: 15px;">Estado</th>
                <th style="padding: 15px;">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($posts as $post)
            <tr>
                <td style="padding: 15px;">{{ $post->id }}</td>
                <td style="padding: 15px;">{{ $post->title }}</td>
                <td style="padding: 15px;">{{ $post->status }}</td>
                <td style="padding: 15px;">

                    <a href="{{ route('posts.edit',$post->id) }}"
                        class="btn-cafe"
   style="margin-right: 10px;">
                        Editar
                    </a>

                  <form method="POST"
      action="{{ route('posts.destroy',$post->id) }}"
      class="form-inline">

    @csrf
    @method('DELETE')

    <button type="submit"
            class="btn-cafe"
            style="margin-left: 10px;">
        Eliminar
    </button>

</form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection