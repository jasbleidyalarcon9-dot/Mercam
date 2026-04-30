<h2>Lista de Productos</h2>
<h2>Lista de Productos</h2>


<a href="{{ route('productos.create') }}">
    <button>Crear producto</button>
</a>

<a href="{{ route('dashboard') }}">
    <button>Volver atrás</button>
</a>

<a href="{{ url('/') }}">
    <button>Inicio</button>
</a>

<br><br>

<table border="1">
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
</tr>
@endforeach

</table>