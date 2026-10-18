<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';

    protected $fillable = [
        'pedido_id',
        'post_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public const ESTADOS = ['pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // atajo: el productor dueño de este renglón (el dueño del producto vendido)
    public function productor()
    {
        return $this->post->user;
    }
}
