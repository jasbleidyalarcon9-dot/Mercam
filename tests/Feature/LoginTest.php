<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearUsuario(string $password = 'password123'): User
    {
        $user = User::factory()->create(['password' => Hash::make($password)]);
        $user->role_id = Role::where('name', 'consumidor')->value('id');
        $user->save();

        return $user;
    }

    public function test_un_usuario_puede_iniciar_sesion_con_credenciales_correctas(): void
    {
        $user = $this->crearUsuario('password123');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_no_se_autentica_con_contrasena_incorrecta(): void
    {
        $user = $this->crearUsuario('password123');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'contrasena-equivocada',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_se_bloquea_el_login_tras_varios_intentos_fallidos(): void
    {
        $user = $this->crearUsuario('password123');

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'contrasena-equivocada',
            ]);
        }

        // el sexto intento, aunque la contraseña esté bien, debe quedar bloqueado
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
