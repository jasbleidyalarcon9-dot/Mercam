@extends('layouts.app')

@section('content')

<h1>Editar publicación</h1>

<form method="POST" action="{{ route('posts.update', $post) }}">
    @csrf
    @method('PUT')

    <label>Título</label>
    <input type="text" name="title" value="{{ $post->title }}" required>

    <label>Slug</label>
    <input type="text" name="slug" value="{{ $post->slug }}" required>

    <label>Contenido</label>
    <textarea name="content" required>{{ $post->content }}</textarea>

    <label>Estado</label>
    <select name="status">
        <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Borrador</option>
        <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Publicado</option>
    </select>

    <br><br>

    <button type="submit">Actualizar</button>
</form>

@endsectionD