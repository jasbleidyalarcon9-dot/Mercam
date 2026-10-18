@extends('layouts.app')

@section('content')
<div class="dashboard-main">
    <h1>Panel de administrador</h1>

    <div class="d-flex flex-wrap" style="gap:20px; margin-top:20px;">
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['usuarios_total'] }}</h3>
            <p style="margin:0; color:#666;">Usuarios totales</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['productores'] }}</h3>
            <p style="margin:0; color:#666;">Productores</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['consumidores'] }}</h3>
            <p style="margin:0; color:#666;">Consumidores</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['productos_publicados'] }}</h3>
            <p style="margin:0; color:#666;">Productos publicados</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['productos_borrador'] }}</h3>
            <p style="margin:0; color:#666;">En borrador</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['pedidos_total'] }}</h3>
            <p style="margin:0; color:#666;">Pedidos totales</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">{{ $stats['renglones_pendientes'] }}</h3>
            <p style="margin:0; color:#666;">Renglones pendientes</p>
        </div>
        <div class="form-post" style="flex:1; min-width:180px; text-align:center;">
            <h3 style="margin:0;">${{ number_format($stats['ingresos_plataforma'], 0, ',', '.') }}</h3>
            <p style="margin:0; color:#666;">Movido en la plataforma</p>
        </div>
    </div>

        <div class="d-flex" style="gap:16px; margin-top:30px;">
        <a href="{{ route('admin.usuarios.index') }}" class="btn-create">Gestionar usuarios</a>
        <a href="{{ route('admin.productos.index') }}" class="btn-create">Moderar productos</a>
        <a href="{{ route('admin.pedidos.index') }}" class="btn-create">Todos los pedidos</a>
        <a href="{{ route('admin.resenas.index') }}" class="btn-create">Todas las reseñas</a>
    </div>
</div>
@endsection
