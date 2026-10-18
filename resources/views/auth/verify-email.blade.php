@extends('layouts.app')

@section('title', 'Verifica tu correo — Mercampo')

@section('content')
<div class="container" style="max-width:600px; text-align:center;">
    <h1>Confirma tu correo</h1>

    <div class="form-edit-post">
        <p>
            Te enviamos un enlace de confirmación a
            <strong>{{ auth()->user()->email }}</strong>.
            Ábrelo desde tu bandeja de entrada (revisa también spam) para
            activar tu cuenta por completo.
        </p>

        @if (session('success'))
            <p style="color:#154105; font-weight:600;">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p style="color:#c0392b; font-weight:600;">{{ session('error') }}</p>
        @endif

        <form action="{{ route('verification.send') }}" method="POST" style="margin-top:16px;">
            @csrf
            <button type="submit">Reenviar enlace de confirmación</button>
        </form>

        <hr style="margin:24px 0; border-color:#eee;">

        <p style="font-weight:600;">¿Escribiste mal tu correo? Ese es el motivo más común de que el enlace nunca llegue.</p>

        @if ($errors->any())
            <div class="success-message" style="background:#c0392b; text-align:left;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('verification.actualizar-correo') }}" method="POST" style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
            @csrf
            @method('PUT')
            <input type="email" name="email" placeholder="Correo correcto" value="{{ old('email') }}" required style="max-width:280px;">
            <button type="submit">Corregir correo</button>
        </form>

        <form action="{{ route('logout') }}" method="POST" style="margin-top:20px;">
            @csrf
            <button type="submit" class="btn-view" style="border:none; background:none;">Cerrar sesión</button>
        </form>
    </div>
</div>
@endsection