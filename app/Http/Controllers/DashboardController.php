<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // El administrador modera la plataforma: llega a su propio panel
        if ($user->role?->name === 'administrador') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role?->name === 'productor') {
            return redirect()->route('productor.dashboard');
        }

        // un consumidor "aterriza" en sus pedidos, que es lo que le importa
        return redirect()->route('pedidos.index');
    }
}
