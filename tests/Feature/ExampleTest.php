<?php

namespace Tests\Feature;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // '/' ahora es el catálogo (PostController::index), que consulta la
        // tabla "posts" — por eso este test por defecto de Laravel también
        // necesita RefreshDatabase, aunque no pruebe nada de negocio en sí.
        $this->seed(RoleSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
