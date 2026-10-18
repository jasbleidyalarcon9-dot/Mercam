@extends('layouts.app')

@section('title', 'Mi perfil — Mercampo')

@section('content')
<div class="perfil-page">
    <div class="perfil-shell">
        <div class="perfil-header">
            <div class="perfil-badge">Mi cuenta</div>
            <h1>Mi perfil</h1>
        </div>

        @if ($errors->any())
            <div class="success-message" style="background:#c0392b; margin-bottom:18px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="perfil-card">
            <div class="perfil-top">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="avatar-preview">
                @else
                    <div class="avatar-preview avatar-fallback">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="perfil-identidad">
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                    <span>{{ $user->role?->label }}</span>
                </div>
            </div>

            <div class="perfil-panel">
                <div class="perfil-panel-header">
                    <span>Datos personales</span>
                </div>

                <form action="{{ route('profile.update-name') }}" method="POST" class="perfil-form">
                    @csrf
                    <div class="perfil-field">
                        <label for="name">Nombre visible</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="255" required>
                    </div>
                    <button type="submit" class="perfil-btn-primary">Guardar nombre</button>
                </form>
            </div>

            <div class="perfil-panel">
                <div class="perfil-panel-header">
                    <span>Foto de perfil</span>
                </div>

                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="perfil-form">
                    @csrf
                    <div class="perfil-field">
                        <label for="avatar">Cambiar foto</label>
                        <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg" required>
                        <small>JPG o PNG, máximo 2MB.</small>
                    </div>
                    <button type="submit" class="perfil-btn-primary">Actualizar foto</button>
                </form>
            </div>

            <div class="perfil-divider"></div>

            <div class="perfil-actions">
                <a href="{{ route('perfil.password.edit') }}" class="perfil-btn-secondary">Cambiar mi contraseña</a>
            </div>
        </div>
    </div>
</div>
@endsection
