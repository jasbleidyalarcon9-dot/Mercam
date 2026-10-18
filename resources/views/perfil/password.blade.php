@extends('layouts.app')

@section('title', 'Cambiar contraseña — Mercampo')

@section('content')
<div class="container" style="max-width:500px;">
    <h1>Cambiar mi contraseña</h1>

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
        <form action="{{ route('perfil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password">Contraseña actual</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div>
                <label for="password">Contraseña nueva (mínimo 8 caracteres)</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirmar contraseña nueva</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit">Guardar contraseña</button>
        </form>
    </div>

    <a href="{{ route('perfil.edit') }}">&larr; Volver a mi perfil</a>
</div>
@endsection
