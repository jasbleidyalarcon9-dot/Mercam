<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_pedidos', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            // Antes: cascadeOnDelete() — borraba en cascada el historial de
            // pedidos si se borraba el producto, descuadrando el total del
            // pedido. Ahora la base de datos RECHAZA el borrado de un
            // producto que ya tiene pedidos asociados (defensa de última
            // línea; el mensaje amigable lo da el controlador antes de
            // siquiera intentarlo).
            $table->foreign('post_id')->references('id')->on('posts')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detalle_pedidos', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
        });
    }
};
