<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'enlace',
        'content',
        'status',
        'precio',
        'stock',
        'unidad',
        'categoria',
        'imagen',
    ];

    protected $casts = [
        'status' => 'string',
        'precio' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function scopePublicados($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDisponibles($query)
    {
        return $query->publicados()->where('stock', '>', 0);
    }

    public function scopeDeCategoria($query, ?string $categoria)
    {
        return $categoria ? $query->where('categoria', $categoria) : $query;
    }

    public function hayStockPara(int $cantidad): bool
    {
        return $this->stock >= $cantidad;
    }
}
