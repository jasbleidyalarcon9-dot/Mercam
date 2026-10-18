@extends('layouts.app')

@section('content')
<div class="product-create-shell">
    <div class="product-create-header">
        <div>
            <span class="product-create-kicker">Mercam productor</span>
            <h1>Publicar producto</h1>
        </div>
        <a href="{{ route('posts.mine') }}" class="product-create-back">&larr; Volver a mis productos</a>
    </div>

    @if ($errors->any())
        <div class="success-message" style="background:#c0392b; margin-bottom:20px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="product-create-card">
        <div class="product-create-aside">
            <span class="product-create-pill">Nuevo producto</span>
            <h2>Cuéntale al cliente qué tan fresco está tu cosecha.</h2>
            <p>Agrega una buena descripción, precio claro y una imagen que destaque la calidad de tus productos.</p>
            <ul>
                <li>Precios competitivos</li>
                <li>Inventario actualizado</li>
                <li>Catálogo listo para vender</li>
            </ul>
        </div>

        <div class="product-create-form-wrap">
            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="product-create-form">
                @csrf

                <div class="product-form-grid">
                    <div class="product-field product-field-full">
                        <label for="title">Título del producto</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="150">
                    </div>

                    <div class="product-field product-field-full">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" maxlength="150"
                               placeholder="ej: aguacate-hass-1kg">
                    </div>

                    <div class="product-field product-field-full">
                        <label for="enlace">Enlace externo</label>
                        <input type="url" id="enlace" name="enlace" value="{{ old('enlace') }}"
                               placeholder="https://...">
                    </div>

                    <div class="product-field product-field-full">
                        <label for="content">Descripción</label>
                        <textarea id="content" name="content" rows="5" required maxlength="5000">{{ old('content') }}</textarea>
                    </div>

                    <div class="product-field">
                        <label for="precio">Precio (COP)</label>
                        <input type="number" id="precio" name="precio" step="0.01" min="0"
                               value="{{ old('precio') }}" required>
                    </div>

                    <div class="product-field">
                        <label for="stock">Cantidad disponible</label>
                        <input type="number" id="stock" name="stock" min="0" step="1"
                               value="{{ old('stock', 0) }}" required>
                    </div>

                    <div class="product-field">
                        <label for="unidad">Unidad de venta</label>
                        <select id="unidad" name="unidad" required>
                            @foreach (['unidad', 'kg', 'libra', 'atado', 'docena', 'bulto'] as $u)
                                <option value="{{ $u }}" {{ old('unidad') === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="product-field">
                        <label for="categoria">Categoría</label>
                        <input type="text" id="categoria" name="categoria" value="{{ old('categoria') }}"
                               placeholder="ej: frutas, verduras, lácteos" maxlength="60">
                    </div>

                    <div class="product-field product-field-full">
                        <label for="imagen">Foto del producto</label>
                        <input type="file" id="imagen" name="imagen" accept="image/png,image/jpeg,image/webp">
                    </div>

                    <div class="product-field product-field-full">
                        <label for="status">Estado</label>
                        <select id="status" name="status" required>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Borrador (solo tú lo ves)</option>
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publicado (visible en el catálogo)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="product-submit-btn">Publicar producto</button>
            </form>
        </div>
    </div>
</div>
@endsection
