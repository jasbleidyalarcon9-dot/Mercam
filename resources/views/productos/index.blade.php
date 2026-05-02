<h2 class="titulo-productos">Lista de Productos</h2>

<a href="{{ route('productos.create') }}">
    <button class="boton-producto">Crear producto</button>
</a>

<a href="{{ route('dashboard') }}">
    <button class="boton-producto">Volver </button>
</a>

<a href="{{ url('/') }}">
    <button class="boton-producto">Inicio</button>
</a>

<br><br>

<table border="1" class="tabla-productos">
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Stock</th>
    </tr>

    @foreach($productos as $producto)
    <tr>
        <td>{{ $producto->nombre }}</td>
        <td>{{ $producto->descripcion }}</td>
        <td>{{ $producto->precio }}</td>
        <td>{{ $producto->stock }}</td>

        <td class="botones-fila">
            <a href="{{ route('productos.edit', $producto->id) }}">
                <button class="boton-producto">Editar</button>
            </a>

            <form action="{{ route('productos.destroy', $producto->id) }}"
                  method="POST"
                  style="display:inline;">
                @csrf
                @method('DELETE')

                <button type="submit" class="boton-producto">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>