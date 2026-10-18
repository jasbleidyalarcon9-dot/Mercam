<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (in_array($user->role?->name, ['productor', 'administrador'], true)) {
            return redirect()->route('productor.dashboard');
        }

        // un consumidor "aterriza" en sus pedidos, que es lo que le importa
        return redirect()->route('pedidos.index');
    }
}
