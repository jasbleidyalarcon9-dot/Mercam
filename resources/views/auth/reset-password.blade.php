@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="form-container">
        <h1>Nueva contraseña</h1>

        @if ($errors->any())
            <div class="success-message" style="background:#c0392b;">
                <ul style="margin:0; padding-left:18px; text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email', $email) }}" required>
            <input type="password" name="password" placeholder="Contraseña nueva (mínimo 8 caracteres)" required>
            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
            <button type="submit">Guardar contraseña</button>
        </form>
    </div>
</div>
@endsection
