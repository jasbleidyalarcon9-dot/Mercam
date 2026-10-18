<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Historial de cumplimiento calculado con contadores condicionales,
        // en una sola consulta (nada de N+1 por cada usuario en la tabla).
        $usuarios = User::with('role')
            ->withCount([
                'detallePedidosComoProductor as ventas_entregadas_count' => fn ($q) => $q->where('estado', 'entregado'),
                'detallePedidosComoProductor as ventas_canceladas_count' => fn ($q) => $q->where('estado', 'cancelado'),
                'pedidos as compras_count',
                'pedidos as compras_con_cancelacion_count' => fn ($q) => $q->whereHas(
                    'detalles',
                    fn ($q2) => $q2->where('estado', 'cancelado')
                ),
            ])
            ->latest()
            ->paginate(20);

        $roles = Role::all();

        return view('admin.usuarios', compact('usuarios', 'roles'));
    }

    public function actualizarRol(UpdateUserRoleRequest $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        $user->role_id = $request->validated()['role_id'];
        $user->save();

        return back()->with('success', "Rol de {$user->name} actualizado.");
    }

    public function suspender(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes suspender tu propia cuenta.');
        }

        $user->activo = false;
        $user->save();

        return back()->with('success', "{$user->name} fue suspendido. Su sesión se cerrará automáticamente.");
    }

    public function activar(User $user)
    {
        $user->activo = true;
        $user->save();

        return back()->with('success', "{$user->name} fue reactivado.");
    }
}