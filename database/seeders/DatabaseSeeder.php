<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Los roles deben existir ANTES de crear usuarios.
        $this->call(RoleSeeder::class);

        $consumidor = Role::where('name', 'consumidor')->first();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // role_id se asigna directo, NUNCA por mass-assignment (ver User::$fillable)
        $user->role_id = $consumidor->id;
        $user->save();
    }
}
