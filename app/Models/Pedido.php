<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function comprador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    // true si TODOS los renglones de TODOS los productores ya fueron entregados
    public function entregadoCompleto(): bool
    {
        return $this->detalles->every(fn ($d) => $d->estado === 'entregado');
    }
}
