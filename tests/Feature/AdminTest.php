<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearUsuarioConRol(string $rol): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->role_id = Role::where('name', $rol)->value('id');
        $user->save();

        return $user;
    }

    public function test_una_cuenta_suspendida_no_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->role_id = Role::where('name', 'consumidor')->value('id');
        $user->activo = false;
        $user->save();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_puede_suspender_a_un_usuario(): void
    {
        $admin = $this->crearUsuarioConRol('administrador');
        $consumidor = $this->crearUsuarioConRol('consumidor');

        $response = $this->actingAs($admin)->put(route('admin.usuarios.suspender', $consumidor));

        $response->assertRedirect();
        $this->assertFalse($consumidor->fresh()->activo);
    }

    public function test_un_admin_no_puede_suspenderse_a_si_mismo(): void
    {
        $admin = $this->crearUsuarioConRol('administrador');

        $this->actingAs($admin)->put(route('admin.usuarios.suspender', $admin));

        $this->assertTrue($admin->fresh()->activo);
    }

    public function test_un_no_administrador_no_puede_suspender_a_nadie(): void
    {
        $productor = $this->crearUsuarioConRol('productor');
        $consumidor = $this->crearUsuarioConRol('consumidor');

        $response = $this->actingAs($productor)->put(route('admin.usuarios.suspender', $consumidor));

        $response->assertForbidden();
        $this->assertTrue($consumidor->fresh()->activo);
    }

    public function test_admin_puede_eliminar_la_resena_de_cualquier_usuario(): void
    {
        $admin = $this->crearUsuarioConRol('administrador');
        $productor = $this->crearUsuarioConRol('productor');
        $autor = $this->crearUsuarioConRol('consumidor');

        $post = $productor->posts()->create([
            'title' => 'Miel de abejas',
            'slug' => 'miel-de-abejas-'.uniqid(),
            'content' => 'Miel pura.',
            'status' => 'published',
            'precio' => 12000,
            'stock' => 5,
            'unidad' => 'unidad',
        ]);

        $resena = $post->resenas()->create([
            'user_id' => $autor->id,
            'comentario' => 'Comentario fuera de lugar que hay que moderar.',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.resenas.destroy', $resena));

        $response->assertRedirect();
        $this->assertDatabaseMissing('resenas', ['id' => $resena->id]);
    }
}