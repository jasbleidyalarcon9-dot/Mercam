<?php

namespace App\Providers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Post;
use App\Models\Resena;
use App\Policies\DetallePedidoPolicy;
use App\Policies\PedidoPolicy;
use App\Policies\PostPolicy;
use App\Policies\ResenaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        Resena::class => ResenaPolicy::class,
        Pedido::class => PedidoPolicy::class,
        DetallePedido::class => DetallePedidoPolicy::class,
    ];

    public function boot(): void
    {
        // El administrador pasa CUALQUIER policy (moderar productos/reseñas
        // de otros, ver cualquier pedido, etc.). Se define en un solo lugar
        // para no repetir "if ($user->isAdmin())" en cada policy.
        Gate::before(function ($user, string $ability) {
            return $user->isAdmin() ? true : null;
        });
    }
}
