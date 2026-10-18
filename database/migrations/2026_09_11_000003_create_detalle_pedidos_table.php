<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('post_id')->comment('producto comprado')->constrained('posts')->cascadeOnDelete();
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2); // "foto" del precio al momento de comprar
            $table->decimal('subtotal', 10, 2);
            // Cada renglón tiene SU PROPIO estado: como un pedido puede traer
            // productos de varios productores distintos, cada productor solo
            // controla el estado de sus propios renglones, nunca los de otro.
            $table->enum('estado', ['pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado'])
                ->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};
