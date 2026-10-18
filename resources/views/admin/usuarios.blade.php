@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Usuarios</h1>

    <table class="posts-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Historial</th>
                <th>Estado</th>
                <th>Cambiar rol</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->role?->label ?? 'Sin rol' }}</td>
                    <td style="font-size:13px;">
                        @if ($usuario->role?->name === 'productor')
                            <span style="color:#154105;">{{ $usuario->ventas_entregadas_count }} entregadas</span><br>
                            <span style="color:#c0392b;">{{ $usuario->ventas_canceladas_count }} canceladas</span>
                        @elseif ($usuario->role?->name === 'consumidor')
                            {{ $usuario->compras_count }} compras
                            @if ($usuario->compras_con_cancelacion_count > 0)
                                <br><span style="color:#c0392b;">{{ $usuario->compras_con_cancelacion_count }} con cancelación</span>
                            @endif
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td>
                        @if ($usuario->activo)
                            <span style="color:#154105; font-weight:600;">Activo</span>
                        @else
                            <span style="color:#c0392b; font-weight:600;">Suspendido</span>
                        @endif
                    </td>
                    <td>
                        @if ($usuario->id === auth()->id())
                            <em style="color:#999;">(tu cuenta)</em>
                        @else
                            <form action="{{ route('admin.usuarios.rol', $usuario) }}" method="POST"
                                  class="d-flex" style="gap:6px;"
                                  onsubmit="return confirm('¿Cambiar el rol de {{ $usuario->name }}?');">
                                @csrf
                                @method('PUT')
                                <select name="role_id">
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}" {{ $usuario->role_id === $rol->id ? 'selected' : '' }}>
                                            {{ $rol->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-edit" style="width:auto; padding:6px 10px;">Guardar</button>
                            </form>
                        @endif
                    </td>
                    <td>
                        @unless ($usuario->id === auth()->id())
                            @if ($usuario->activo)
                                <form action="{{ route('admin.usuarios.suspender', $usuario) }}" method="POST"
                                      onsubmit="return confirm('¿Suspender a {{ $usuario->name }}? No podrá iniciar sesión hasta que lo reactives.');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-delete" style="width:auto; padding:6px 10px;">Suspender</button>
                                </form>
                            @else
                                <form action="{{ route('admin.usuarios.activar', $usuario) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-view" style="width:auto; padding:6px 10px;">Reactivar</button>
                                </form>
                            @endif
                        @endunless
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $usuarios->links() }}
    </div>

    <a href="{{ route('admin.dashboard') }}">&larr; Volver al panel</a>
</div>
@endsection