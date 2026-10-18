<?php

namespace Tests\Feature;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReglasDeNegocioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearUsuarioConRol(string $rol): User
    {
        $user = User::factory()->create();
        $user->role_id = Role::where('name', $rol)->value('id');
        $user->save();

        return $user;
    }

    private function crearProducto(User $productor, int $stock = 10): Post
    {
        return $productor->posts()->create([
            'title' => 'Queso campesino',
            'slug' => 'queso-campesino-'.uniqid(),
            'content' => 'Queso fresco de finca.',
            'status' => 'published',
            'precio' => 8000,
            'stock' => $stock,
            'unidad' => 'unidad',
        ]);
    }

    public function test_no_se_puede_eliminar_un_producto_con_pedidos_asociados(): void
    {
        $productor = $this->crearUsuarioConRol('productor');
        $comprador = $this->crearUsuarioConRol('consumidor');
        $post = $this->crearProducto($productor);

        $pedido = Pedido::create(['user_id' => $comprador->id, 'total' => 8000]);
        DetallePedido::create([
            'pedido_id' => $pedido->id,
            'post_id' => $post->id,
            'cantidad' => 1,
            'precio_unitario' => 8000,
            'subtotal' => 8000,
        ]);

        $response = $this->actingAs($productor)->delete(route('posts.destroy', $post));

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        $this->assertDatabaseHas('detalle_pedidos', ['post_id' => $post->id]);
    }

    public function test_no_se_puede_dejar_resena_sin_haber_comprado_el_producto(): void
    {
        $productor = $this->crearUsuarioConRol('productor');
        $comprador = $this->crearUsuarioConRol('consumidor');
        $post = $this->crearProducto($productor);

        $response = $this->actingAs($comprador)->post(route('resenas.store', $post), [
            'comentario' => 'Excelente producto, muy fresco.',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('resenas', 0);
    }

    public function test_se_puede_dejar_resena_si_el_pedido_ya_fue_entregado(): void
    {
        $productor = $this->crearUsuarioConRol('productor');
        $comprador = $this->crearUsuarioConRol('consumidor');
        $post = $this->crearProducto($productor);

        $pedido = Pedido::create(['user_id' => $comprador->id, 'total' => 8000]);
        DetallePedido::create([
            'pedido_id' => $pedido->id,
            'post_id' => $post->id,
            'cantidad' => 1,
            'precio_unitario' => 8000,
            'subtotal' => 8000,
            'estado' => 'entregado',
        ]);

        $response = $this->actingAs($comprador)->post(route('resenas.store', $post), [
            'comentario' => 'Excelente producto, muy fresco.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resenas', ['post_id' => $post->id, 'user_id' => $comprador->id]);
    }

    public function test_el_comprador_puede_cancelar_un_renglon_pendiente_y_se_restaura_el_stock(): void
    {
        $productor = $this->crearUsuarioConRol('productor');
        $comprador = $this->crearUsuarioConRol('consumidor');
        $post = $this->crearProducto($productor, stock: 5);

        $pedido = Pedido::create(['user_id' => $comprador->id, 'total' => 8000]);
        $detalle = DetallePedido::create([
            'pedido_id' => $pedido->id,
            'post_id' => $post->id,
            'cantidad' => 2,
            'precio_unitario' => 8000,
            'subtotal' => 16000,
            'estado' => 'pendiente',
        ]);

        $response = $this->actingAs($comprador)->put(route('pedidos.cancelar', $detalle));

        $response->assertRedirect();
        $this->assertSame('cancelado', $detalle->fresh()->estado);
        $this->assertSame(7, $post->fresh()->stock); // 5 + 2 devueltas
    }

    public function test_el_productor_no_puede_cancelar_el_pedido_de_un_comprador(): void
    {
        $productor = $this->crearUsuarioConRol('productor');
        $comprador = $this->crearUsuarioConRol('consumidor');
        $post = $this->crearProducto($productor);

        $pedido = Pedido::create(['user_id' => $comprador->id, 'total' => 8000]);
        $detalle = DetallePedido::create([
            'pedido_id' => $pedido->id,
            'post_id' => $post->id,
            'cantidad' => 1,
            'precio_unitario' => 8000,
            'subtotal' => 8000,
            'estado' => 'pendiente',
        ]);

        $response = $this->actingAs($productor)->put(route('pedidos.cancelar', $detalle));

        $response->assertForbidden();
        $this->assertSame('pendiente', $detalle->fresh()->estado);
    }
}
