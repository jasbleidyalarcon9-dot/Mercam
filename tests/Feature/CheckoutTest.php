<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearProducto(int $stock = 10, float $precio = 5000): Post
    {
        $productor = User::factory()->create();
        $productor->role_id = Role::where('name', 'productor')->value('id');
        $productor->save();

        return $productor->posts()->create([
            'title' => 'Panela orgánica',
            'slug' => 'panela-organica-'.uniqid(),
            'content' => 'Panela producida artesanalmente.',
            'status' => 'published',
            'precio' => $precio,
            'stock' => $stock,
            'unidad' => 'kg',
        ]);
    }

    private function crearConsumidor(): User
    {
        $user = User::factory()->create();
        $user->role_id = Role::where('name', 'consumidor')->value('id');
        $user->save();

        return $user;
    }

    public function test_el_checkout_crea_el_pedido_y_descuenta_el_stock(): void
    {
        $post = $this->crearProducto(stock: 10, precio: 5000);
        $comprador = $this->crearConsumidor();

        $this->actingAs($comprador)->post(route('carrito.store', $post), ['cantidad' => 3]);
        $response = $this->actingAs($comprador)->post(route('carrito.checkout'));

        $this->assertSame(7, $post->fresh()->stock);

        $this->assertDatabaseHas('pedidos', [
            'user_id' => $comprador->id,
            'total' => 15000,
        ]);

        $this->assertDatabaseHas('detalle_pedidos', [
            'post_id' => $post->id,
            'cantidad' => 3,
            'precio_unitario' => 5000,
            'subtotal' => 15000,
            'estado' => 'pendiente',
        ]);

        $response->assertRedirect();
    }

    public function test_el_checkout_falla_si_no_hay_stock_suficiente(): void
    {
        $post = $this->crearProducto(stock: 2, precio: 5000);
        $comprador = $this->crearConsumidor();

        // agrega 2 (todo el stock disponible)
        $this->actingAs($comprador)->post(route('carrito.store', $post), ['cantidad' => 2]);

        // alguien más se adelanta y agota el producto entre que se agregó al carrito y se pagó
        $post->update(['stock' => 0]);

        $response = $this->actingAs($comprador)->post(route('carrito.checkout'));

        $response->assertRedirect(route('carrito.index'));
        $this->assertDatabaseCount('pedidos', 0);
        $this->assertSame(0, $post->fresh()->stock);
    }

    public function test_un_consumidor_no_puede_comprar_su_propio_producto_marcado_por_otro_usuario(): void
    {
        // este test documenta que el user_id del pedido SIEMPRE sale de
        // auth()->id(), nunca de un valor enviado por el formulario
        $post = $this->crearProducto();
        $comprador = $this->crearConsumidor();

        $this->actingAs($comprador)->post(route('carrito.store', $post), ['cantidad' => 1]);
        $this->actingAs($comprador)->post(route('carrito.checkout'));

        $this->assertDatabaseHas('pedidos', ['user_id' => $comprador->id]);
    }
}
