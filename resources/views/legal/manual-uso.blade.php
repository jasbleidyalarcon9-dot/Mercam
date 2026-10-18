@extends('layouts.app')

@section('title', 'Manual de uso — Mercam')

@section('content')
<div class="container" style="max-width: 1000px; padding: 40px 20px 60px;">
    <div class="form-edit-post" style="text-align:left; background:#fff; border:1px solid #e8e0d4; border-radius:18px; padding:30px 28px; box-shadow: 0 10px 24px rgba(49, 36, 20, 0.06);">
        <h1 style="font-family: Georgia, 'Times New Roman', serif; color:#214d13; margin-bottom:18px;">Manual de uso de Mercam</h1>
        <p style="color:#5c544d; line-height:1.7; margin-bottom:22px;">
            Mercam es una plataforma digital para conectar productores agrícolas con compradores que desean productos frescos, locales y de temporada.
            Aquí te explicamos cómo registrarte, comprar, vender y gestionar tu cuenta de la forma más fácil.
        </p>

        <div style="display:grid; gap:22px;">
            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">1. Crear tu cuenta</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    En la parte superior derecha encontrarás las opciones de <strong>Iniciar sesión</strong> y <strong>Registrarme</strong>.
                    Si vas a vender, crea una cuenta de productor. Si vas a comprar, crea una cuenta de consumidor.
                    Una vez creada la cuenta, podrás ingresar a tu panel y comenzar a usar la plataforma.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">2. Explorar productos en el catálogo</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    Desde la página principal podrás ver productos frescos, buscar por nombre y filtrar por categorías.
                    Cada producto muestra su nombre, productor, precio, unidad de venta, stock disponible y una imagen para identificarlo fácilmente.
                    Para ver más detalles, solo debes hacer clic sobre el producto.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">3. Comprar productos</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    Cuando encuentres un producto que te interese, entra a su detalle y selecciona la cantidad que deseas comprar.
                    Luego se agregará al carrito. Puedes revisar el carrito para ajustar cantidades o eliminar artículos antes de confirmar tu compra.
                    Cuando estés listo, finaliza tu pedido para enviarlo al productor.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">4. Consultar carrito y pedidos</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    En la sección de <strong>Carrito</strong> podrás revisar todos los productos seleccionados, actualizar cantidades y confirmar la compra.
                    En la vista de <strong>Pedidos</strong> podrás ver el estado del pedido, como pendiente, confirmado, entregado o cancelado.
                    Así tendrás siempre control de lo que has solicitado y del avance de cada compra.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">5. Publicar productos como productor</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    Si tienes cuenta de productor, puedes publicar tus productos desde <strong>Mis productos</strong> o desde el panel del productor.
                    Para crear un producto debes completar: título, descripción, precio, cantidad disponible, unidad de venta, categoría e imagen.
                    También puedes editar la información, despublicar un producto o mantenerlo en borrador mientras preparas la venta.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">6. Revisar pedidos recibidos</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    En el panel del productor podrás ver los pedidos recibidos, identificar al comprador, revisar la cantidad y el subtotal de cada producto.
                    Además, puedes actualizar el estado del pedido para marcarlo como pendiente, confirmado, entregado o cancelado.
                    Esta vista te ayuda a organizar la operación de ventas con mayor rapidez.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">7. Gestionar tu perfil y notificaciones</h2>
                <p style="color:#5c544d; line-height:1.7; margin:0;">
                    Desde tu perfil puedes actualizar tus datos, cambiar tu nombre, subir una foto de perfil y gestionar tu avatar.
                    También puedes revisar tus notificaciones para estar al tanto de nuevos pedidos, cambios de estado, ventas realizadas y mensajes importantes.
                    Además, puedes cambiar tu contraseña si lo necesitas.
                </p>
            </section>

            <section>
                <h2 style="font-size:20px; color:#2b1c11; margin-bottom:8px;">8. Recomendaciones importantes</h2>
                <ul style="margin:0; padding-left:20px; color:#5c544d; line-height:1.8;">
                    <li>Usa datos reales para que tus compras y ventas queden bien registradas.</li>
                    <li>Sube imágenes claras para que tus productos se vean más atractivos.</li>
                    <li>Mantén el stock actualizado para evitar errores en las ventas.</li>
                    <li>Revisa periódicamente tus notificaciones para responder a pedidos y cambios de estado.</li>
                    <li>Si tienes dudas, consulta esta guía o contacta con soporte.</li>
                </ul>
            </section>
        </div>

        <div style="margin-top:28px; padding-top:18px; border-top:1px solid #e8e0d4;">
            <p style="margin:0; color:#2b1c11; font-weight:700;">Contacto</p>
            <p style="margin:8px 0 0; color:#5c544d;">
                Correo: <a href="mailto:tuniversidad801@gmail.com" style="color:#214d13; text-decoration:none; font-weight:700;">tuniversidad801@gmail.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
