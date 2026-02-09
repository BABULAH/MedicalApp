<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Liste de tous les rôles
        $roles = [
            'super_admin',
            'admin',
            'doctor',
            'user',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $this->command->info('✅ Tous les rôles ont été créés ou existent déjà.');
    }
}
