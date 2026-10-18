<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_un_visitante_puede_registrarse_como_productor(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tipo_cuenta' => 'productor',
            'acepta_tratamiento_datos' => '1',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();

        $user = User::where('email', 'ana@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('productor', $user->role->name);
        $this->assertNotNull($user->datos_aceptados_en);
    }

    public function test_no_se_puede_registrar_como_administrador(): void
    {
        $response = $this->post('/register', [
            'name' => 'Intento Admin',
            'email' => 'intento@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tipo_cuenta' => 'administrador', // fuera del enum permitido
            'acepta_tratamiento_datos' => '1',
        ]);

        $response->assertSessionHasErrors('tipo_cuenta');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'intento@example.com']);
    }

    public function test_no_se_puede_registrar_sin_aceptar_tratamiento_de_datos(): void
    {
        $response = $this->post('/register', [
            'name' => 'Sin Consentimiento',
            'email' => 'sinconsentimiento@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tipo_cuenta' => 'consumidor',
            // 'acepta_tratamiento_datos' omitido a propósito
        ]);

        $response->assertSessionHasErrors('acepta_tratamiento_datos');
        $this->assertGuest();
    }
}
