<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearProductor(): User
    {
        $user = User::factory()->create();
        $user->role_id = Role::where('name', 'productor')->value('id');
        $user->save();

        return $user;
    }

    private function crearProductoDe(User $productor): Post
    {
        return $productor->posts()->create([
            'title' => 'Aguacates Hass',
            'slug' => 'aguacates-hass-'.uniqid(),
            'content' => 'Aguacates frescos de la finca.',
            'status' => 'published',
            'precio' => 5000,
            'stock' => 20,
            'unidad' => 'kg',
        ]);
    }

    public function test_un_productor_no_puede_editar_el_producto_de_otro(): void
    {
        $dueno = $this->crearProductor();
        $otro = $this->crearProductor();
        $post = $this->crearProductoDe($dueno);

        $response = $this->actingAs($otro)->put(route('posts.update', $post), [
            'title' => 'Intento de robo',
            'content' => 'contenido cualquiera',
            'status' => 'published',
            'precio' => 1,
            'stock' => 1,
            'unidad' => 'kg',
        ]);

        $response->assertForbidden();
        $this->assertSame('Aguacates Hass', $post->fresh()->title);
    }

    public function test_un_productor_no_puede_eliminar_el_producto_de_otro(): void
    {
        $dueno = $this->crearProductor();
        $otro = $this->crearProductor();
        $post = $this->crearProductoDe($dueno);

        $response = $this->actingAs($otro)->delete(route('posts.destroy', $post));

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_el_dueno_si_puede_editar_su_propio_producto(): void
    {
        $dueno = $this->crearProductor();
        $post = $this->crearProductoDe($dueno);

        $response = $this->actingAs($dueno)->put(route('posts.update', $post), [
            'title' => 'Aguacates Hass — cosecha nueva',
            'content' => 'Aguacates frescos de la finca.',
            'status' => 'published',
            'precio' => 5500,
            'stock' => 15,
            'unidad' => 'kg',
        ]);

        $response->assertRedirect(route('posts.mine'));
        $this->assertSame('Aguacates Hass — cosecha nueva', $post->fresh()->title);
    }

    public function test_un_consumidor_no_puede_crear_productos(): void
    {
        $consumidor = User::factory()->create();
        $consumidor->role_id = Role::where('name', 'consumidor')->value('id');
        $consumidor->save();

        $response = $this->actingAs($consumidor)->get(route('posts.create'));

        $response->assertForbidden();
    }
}
