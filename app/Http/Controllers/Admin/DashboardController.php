<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'usuarios_total' => User::count(),
            'productores' => User::whereHas('role', fn ($q) => $q->where('name', 'productor'))->count(),
            'consumidores' => User::whereHas('role', fn ($q) => $q->where('name', 'consumidor'))->count(),
            'productos_publicados' => Post::where('status', 'published')->count(),
            'productos_borrador' => Post::where('status', 'draft')->count(),
            'pedidos_total' => Pedido::count(),
            'renglones_pendientes' => DetallePedido::where('estado', 'pendiente')->count(),
            'ingresos_plataforma' => DetallePedido::whereIn('estado', ['confirmado', 'enviado', 'entregado'])->sum('subtotal'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
