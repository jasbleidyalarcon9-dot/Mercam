@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="form-container">
        <h1>Crear cuenta</h1>

        @if ($errors->any())
            <div class="success-message" style="background:#c0392b;">
                <ul style="margin:0; padding-left:18px; text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" name="name" placeholder="Nombre completo"
                   value="{{ old('name') }}" required autofocus>

            <input type="email" name="email" placeholder="Correo electrónico"
                   value="{{ old('email') }}" required>

            <input type="password" name="password" placeholder="Contraseña (mínimo 8 caracteres)" required>

            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>

            {{-- Tipo de cuenta: define el rol (productor/consumidor).
                 "administrador" NUNCA aparece aquí; ese rol solo se asigna
                 manualmente en la base de datos. --}}
            <div style="text-align:left; color:#000000; margin-bottom:16px;">
                <p style="margin-bottom:8px; font-weight:600;">Quiero registrarme como:</p>

                <label style="display:block; margin-bottom:6px; cursor:pointer;">
                    <input type="radio" name="tipo_cuenta" value="productor"
                           {{ old('tipo_cuenta') === 'productor' ? 'checked' : '' }} required>
                    Productor — quiero vender mis productos agrícolas
                </label>

                <label style="display:block; cursor:pointer;">
                    <input type="radio" name="tipo_cuenta" value="consumidor"
                           {{ old('tipo_cuenta') === 'consumidor' ? 'checked' : '' }} required>
                    Consumidor — quiero comprar productos
                </label>
            </div>

            <div style="text-align:left; color:#000000; margin-bottom:16px;">
                <label style="display:block; cursor:pointer;">
                    <input type="checkbox" name="acepta_tratamiento_datos" value="1"
                           {{ old('acepta_tratamiento_datos') ? 'checked' : '' }} required>
                    Acepto la
                    <a href="{{ route('legal.politica-datos') }}" target="_blank" style="color:#2b1717; text-decoration:underline;">
                        política de tratamiento de datos personales
                    </a>
                    (Ley 1581 de 2012).
                </label>
            </div>

            <button type="submit">Registrarme</button>

            <div class="auth-links">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>
</div>
@endsection
