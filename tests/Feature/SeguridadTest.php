<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeguridadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_la_contrasena_no_se_guarda_en_texto_plano(): void
    {
        $user = User::create([
            'name' => 'Ana López',
            'email' => 'ana@mercam.test',
            'password' => Hash::make('MiPasswordSegura123'),
        ]);

        $this->assertNotSame('MiPasswordSegura123', $user->password);
        $this->assertTrue(Hash::check('MiPasswordSegura123', $user->password));
        $this->assertStringNotContainsString('MiPasswordSegura123', $user->password);
    }

    public function test_la_sesion_se_regenera_al_iniciar_sesion(): void
    {
        $rol = Role::where('name', 'consumidor')->firstOrFail();

        $user = User::create([
            'name' => 'Usuario Login',
            'email' => 'login@mercam.test',
            'password' => Hash::make('Password123'),
        ]);
        $user->role_id = $rol->id;
        $user->save();

        $this->app['session']->start();
        $idAntes = $this->app['session']->getId();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123',
        ]);

        $idDespues = $this->app['session']->getId();

        $this->assertNotSame($idAntes, $idDespues);
    }

    public function test_role_id_no_se_puede_asignar_por_mass_assignment(): void
    {
        $rolAdmin = Role::where('name', 'administrador')->firstOrFail();

        $user = User::create([
            'name' => 'Intento Admin',
            'email' => 'admin-intento@mercam.test',
            'password' => Hash::make('Password123'),
            'role_id' => $rolAdmin->id,
        ]);

        $this->assertNull($user->role_id);
        $this->assertNotSame($rolAdmin->id, $user->role_id);
    }

    public function test_el_dominio_del_correo_debe_ser_valido(): void
    {
        $response = $this->post('/register', [
            'name' => 'Usuario Invalido',
            'email' => 'usuario@dominio-falso-xyz.invalid',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tipo_cuenta' => 'consumidor',
            'acepta_tratamiento_datos' => '1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'usuario@dominio-falso-xyz.invalid']);
    }

    public function test_no_se_puede_registrar_sin_aceptar_tratamiento_de_datos(): void
    {
        $response = $this->post('/register', [
            'name' => 'Usuario Sin Consentimiento',
            'email' => 'sinconsentimiento@mercam.test',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'tipo_cuenta' => 'consumidor',
            // acepta_tratamiento_datos deliberadamente omitido
        ]);

        $response->assertSessionHasErrors('acepta_tratamiento_datos');
        $this->assertDatabaseMissing('users', ['email' => 'sinconsentimiento@mercam.test']);
    }

    public function test_las_rutas_protegidas_rechazan_visitantes_sin_sesion(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_el_formulario_de_registro_incluye_token_csrf(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('name="_token"', false);
    }
}