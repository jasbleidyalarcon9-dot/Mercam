<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Datos de demostración para el despliegue en la nube (y para la demo del chatbot).
 *
 * - No usa Faker ni factories: funciona en producción (composer install --no-dev).
 * - Es idempotente: se puede ejecutar en cada arranque sin duplicar nada.
 * - SEGURIDAD: las cuentas solo se crean si se definen sus contraseñas en variables
 *   de entorno. El repositorio es público, así que nunca hay contraseñas en el código.
 *
 *   DEMO_PASSWORD   -> cuentas demo de productor y consumidor
 *   ADMIN_EMAIL / ADMIN_PASSWORD -> cuenta de administrador (opcional)
 */
class DemoSeeder extends Seeder
{
    private const PRODUCTOS = [
        ['title' => 'Papa pastusa', 'categoria' => 'papa', 'precio' => 2800, 'stock' => 120, 'unidad' => 'kg', 'imagen' => 'demo/papa.png',
            'content' => 'Papa pastusa recién cosechada en las veredas de Ubaté. Ideal para sopas, purés y fritas.'],
        ['title' => 'Tomate chonto', 'categoria' => 'verduras', 'precio' => 4200, 'stock' => 60, 'unidad' => 'kg', 'imagen' => 'demo/tomat.png',
            'content' => 'Tomate chonto maduro, cultivado sin intermediarios. Perfecto para guisos y ensaladas.'],
        ['title' => 'Lechuga crespa', 'categoria' => 'verduras', 'precio' => 2000, 'stock' => 40, 'unidad' => 'unidad', 'imagen' => 'demo/lechuga.png',
            'content' => 'Lechuga crespa fresca, cosechada el mismo día de la entrega.'],
        ['title' => 'Manzana roja', 'categoria' => 'frutas', 'precio' => 6500, 'stock' => 35, 'unidad' => 'kg', 'imagen' => 'demo/manzana.png',
            'content' => 'Manzana roja dulce y crocante, de cultivo familiar.'],
        ['title' => 'Banano criollo', 'categoria' => 'frutas', 'precio' => 3000, 'stock' => 50, 'unidad' => 'kg', 'imagen' => 'demo/banano.png',
            'content' => 'Banano criollo en su punto, rico en potasio. Ideal para el desayuno y los jugos.'],
        ['title' => 'Queso campesino', 'categoria' => 'lacteos', 'precio' => 18000, 'stock' => 25, 'unidad' => 'libra', 'imagen' => null,
            'content' => 'Queso campesino artesanal elaborado con leche de la región lechera de Ubaté.'],
        ['title' => 'Arveja verde', 'categoria' => 'granos y semillas', 'precio' => 7000, 'stock' => 0, 'unidad' => 'kg', 'imagen' => null,
            'content' => 'Arveja verde desgranada. Temporada terminada: pronto vuelve a estar disponible.'],
    ];

    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $password = env('DEMO_PASSWORD');

        if (blank($password)) {
            $this->command?->warn('DemoSeeder: define DEMO_PASSWORD para crear las cuentas y productos de demostración.');

            return;
        }

        $productor = $this->cuenta('productor.demo@mercam.app', 'Finca La Esperanza (demo)', 'productor', $password);
        $this->cuenta('consumidor.demo@mercam.app', 'Consumidor Demo', 'consumidor', $password);

        if (filled(env('ADMIN_EMAIL')) && filled(env('ADMIN_PASSWORD'))) {
            $this->cuenta(env('ADMIN_EMAIL'), 'Administrador Mercam', 'administrador', env('ADMIN_PASSWORD'));
        }

        foreach (self::PRODUCTOS as $producto) {
            $slug = Str::slug($producto['title']).'-demo';

            if (Post::where('slug', $slug)->exists()) {
                continue; // no pisa cambios hechos desde la plataforma (stock, precio...)
            }

            $productor->posts()->create($producto + ['slug' => $slug, 'status' => 'published']);
        }
    }

    private function cuenta(string $email, string $nombre, string $rol, string $password): User
    {
        $user = User::firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->fill([
                'name' => $nombre,
                'password' => Hash::make($password),
                'datos_aceptados_en' => now(),
            ]);
            // Campos protegidos: se asignan directo, nunca por mass-assignment
            $user->role_id = Role::where('name', $rol)->value('id');
            $user->email_verified_at = now();
            $user->save();
        }

        return $user;
    }
}
