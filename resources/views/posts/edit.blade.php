@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar producto</h1>

    @if ($errors->any())
        <div class="success-message" style="background:#c0392b;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-edit-post">
        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="title">Título del producto</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required maxlength="150">
            </div>

            <div>
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" maxlength="150">
            </div>

            <div>
                <label for="enlace">Enlace (opcional)</label>
                <input type="url" id="enlace" name="enlace" value="{{ old('enlace', $post->enlace) }}"
                       placeholder="https://...">
            </div>

            <div>
                <label for="content">Descripción</label>
                <textarea id="content" name="content" rows="5" required maxlength="5000">{{ old('content', $post->content) }}</textarea>
            </div>

            <div>
                <label for="precio">Precio (COP)</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0"
                       value="{{ old('precio', $post->precio) }}" required>
            </div>

            <div>
                <label for="stock">Cantidad disponible</label>
                <input type="number" id="stock" name="stock" min="0" step="1"
                       value="{{ old('stock', $post->stock) }}" required>
            </div>

            <div>
                <label for="unidad">Unidad de venta</label>
                <select id="unidad" name="unidad" required>
                    @foreach (['unidad', 'kg', 'libra', 'atado', 'docena', 'bulto'] as $u)
                        <option value="{{ $u }}" {{ old('unidad', $post->unidad) === $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="categoria">Categoría (opcional)</label>
                <input type="text" id="categoria" name="categoria" value="{{ old('categoria', $post->categoria) }}" maxlength="60">
            </div>

            @if ($post->imagen)
                <div>
                    <label>Foto actual</label>
                    <img src="{{ Storage::url($post->imagen) }}" alt="{{ $post->title }}"
                         style="max-width:160px; border-radius:8px; display:block; margin-bottom:8px;">
                </div>
            @endif

            <div>
                <label for="imagen">Reemplazar foto (opcional, jpg/png/webp, máx. 2MB)</label>
                <input type="file" id="imagen" name="imagen" accept="image/png,image/jpeg,image/webp">
            </div>

            <div>
                <label for="status">Estado</label>
                <select id="status" name="status" required>
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Borrador (solo tú lo ves)</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Publicado (visible en el catálogo)</option>
                </select>
            </div>

            <button type="submit">Guardar cambios</button>
        </form>
    </div>

    <a href="{{ route('posts.mine') }}">&larr; Volver a mis productos</a>
</div>
@endsection
