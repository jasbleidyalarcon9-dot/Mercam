@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="form-container">
        <div class="login-brand">
            <img src="{{ asset('img/Logomercam.png') }}" alt="Logo Mercam">
            <div>
                <span>Mercam</span>
                <small>Productos del campo</small>
            </div>
        </div>

        <h1>Iniciar sesión</h1>
        <p class="login-subtitle">Accede a tu cuenta y compra productos frescos directamente de productores locales.</p>

        @if ($errors->any())
            <div class="success-message" style="background:#c0392b;">
                <ul style="margin:0; padding-left:18px; text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label class="auth-field">
                <span>Correo electrónico</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>

            <label class="auth-field">
                <span>Contraseña</span>
                <input type="password" name="password" required>
            </label>

            <button type="submit">Entrar</button>

            <div class="auth-links">
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
            </div>
        </form>
    </div>
</div>
@endsection

