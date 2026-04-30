@extends('layouts.app')

@section('content')

<div class="container">

<h1>Nueva publicación</h1>

<form method="POST" action="{{ route('posts.store') }}">
@csrf

<label>Título</label>
<input type="text" name="title" value="{{ old('title') }}" required>

<label>Slug</label>
<input type="text" name="slug" value="{{ old('slug') }}" required>

<label>Contenido</label>
<textarea name="content" required>{{ old('content') }}</textarea>

<label>Estado</label>
<select name="status">
<option value="draft">Borrador</option>
<option value="published">Publicado</option>
</select>

<br><br>

<button type="submit">Guardar publicación</button>

</form>

<hr>

<h2>Registrar Producto</h2>

<form action="{{ route('productos.store') }}" method="POST">
@csrf

<label>Nombre</label>
<input type="text" name="nombre" value="{{ old('nombre') }}" required>

<label>Descripción</label>
<textarea name="descripcion" required>{{ old('descripcion') }}</textarea>

<label>Precio</label>
<input type="number" name="precio" value="{{ old('precio') }}" required>

<label>Stock</label>
<input type="number" name="stock" value="{{ old('stock') }}" required>

<br><br>

<button type="submit">Guardar producto</button>

</form>

</div>

@endsection