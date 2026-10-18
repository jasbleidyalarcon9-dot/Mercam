<?php

return [
    App\Providers\AppServiceProvider::class,
    // Registra las policies y el Gate::before que da permisos de moderación al administrador
    App\Providers\AuthServiceProvider::class,
];
