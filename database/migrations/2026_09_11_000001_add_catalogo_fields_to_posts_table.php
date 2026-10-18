<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->default(0)->after('content');
            $table->unsignedInteger('stock')->default(0)->after('precio');
            $table->string('unidad', 30)->default('unidad')->after('stock'); // kg, atado, unidad, docena...
            $table->string('categoria', 60)->nullable()->after('unidad');
            $table->string('imagen')->nullable()->after('categoria'); // ruta en storage/app/public
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['precio', 'stock', 'unidad', 'categoria', 'imagen']);
        });
    }
};
