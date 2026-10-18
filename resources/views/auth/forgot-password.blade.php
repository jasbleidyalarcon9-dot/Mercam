@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="form-container">
        <h1>Recuperar contraseña</h1>

        @if ($errors->any())
            <div class="success-message" style="background:#c0392b;">
                <ul style="margin:0; padding-left:18px; text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p style="color:#fff;">Escribe tu correo y te enviaremos un enlace para crear una contraseña nueva.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
            <button type="submit">Enviar enlace</button>
            <a href="{{ route('login') }}">Volver a iniciar sesión</a>
        </form>
    </div>
</div>
@endsection
