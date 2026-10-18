<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'administrador', 'label' => 'Administrador'],
            ['name' => 'productor', 'label' => 'Productor agrícola'],
            ['name' => 'consumidor', 'label' => 'Consumidor'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
