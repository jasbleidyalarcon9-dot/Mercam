@extends('layouts.app')

@section('title', 'Política de tratamiento de datos — Mercampo')

@section('content')
<div class="container" style="max-width:800px;">
    <h1>Política de tratamiento de datos personales</h1>
    <p style="color:#666;">De acuerdo con la Ley 1581 de 2012 y el Decreto 1377 de 2013 de Colombia.</p>

    <div class="form-edit-post" style="text-align:left;">
        <h2 style="font-size:20px;">1. Responsable del tratamiento</h2>
        <p>Mercampo, plataforma para la comercialización directa de productos agrícolas
        de pequeños productores de San Diego de Ubaté, es responsable del tratamiento
        de los datos personales que usted suministra al registrarse y usar la plataforma.</p>

        <h2 style="font-size:20px;">2. Datos que recolectamos</h2>
        <p>Nombre, correo electrónico, contraseña (almacenada cifrada, nunca en texto
        plano), foto de perfil (opcional), y la información de los productos, pedidos
        y reseñas que usted mismo registra al usar la plataforma.</p>

        <h2 style="font-size:20px;">3. Finalidad</h2>
        <p>Los datos se usan únicamente para: crear y gestionar su cuenta, permitir la
        publicación y compra de productos, procesar pedidos, comunicar el estado de sus
        pedidos, y garantizar la seguridad de la plataforma. No vendemos ni compartimos
        sus datos con terceros para fines publicitarios.</p>

        <h2 style="font-size:20px;">4. Sus derechos</h2>
        <p>Como titular de los datos usted tiene derecho a conocer, actualizar, rectificar
        y solicitar la eliminación de su información, así como a revocar esta autorización
        en cualquier momento, escribiendo al correo de contacto de la plataforma.</p>

        <h2 style="font-size:20px;">5. Seguridad</h2>
        <p>Las contraseñas se almacenan cifradas mediante algoritmos estándar de la
        industria. El acceso a la información de cada usuario está restringido: cada
        persona solo puede ver y modificar su propia información, productos, pedidos
        y reseñas.</p>
    </div>

    <a href="{{ url()->previous() }}">&larr; Volver</a>
</div>
@endsection
