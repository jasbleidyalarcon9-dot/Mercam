<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Post;
use App\Models\Role;
use App\Models\Resena;
use App\Models\Pedido;
use App\Models\DetallePedido;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Por defecto en PHP (además del ->default(true) de la migración): así
    // un usuario recién instanciado en memoria, antes de guardarse o
    // recargarse de la BD, nunca queda con 'activo' en null por accidente.
    protected $attributes = [
        'activo' => true,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'datos_aceptados_en',
        // OJO: 'role_id' NO está aquí a propósito.
        // El rol nunca se asigna por mass-assignment (ni desde el formulario
        // de registro ni desde el de perfil); se fija explícitamente en el
        // controlador de registro y solo se puede cambiar por un administrador.
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'datos_aceptados_en' => 'datetime',
            'activo' => 'boolean',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    // Pedidos donde este usuario es el COMPRADOR
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    // Renglones vendidos por este usuario como PRODUCTOR (a través de sus posts)
    public function detallePedidosComoProductor()
    {
        return $this->hasManyThrough(DetallePedido::class, Post::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $name): bool
    {
        return $this->role?->name === $name;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('administrador');
    }

    public function estaSuspendido(): bool
    {
        return !$this->activo;
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyEmailQueued());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordQueued($token));
    }
}