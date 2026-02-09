<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le rôle super_admin depuis la table roles
        $role = Role::where('name', 'super_admin')->first();

        if (!$role) {
            $this->command->error('⚠️ Le rôle super_admin n\'existe pas dans la table roles. Veuillez le créer avant de lancer ce seeder.');
            return;
        }

        // Créer ou récupérer l'utilisateur super admin
        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'password'   => Hash::make('Passer123'), // mot de passe initial
            ]
        );

        // Assigner uniquement le rôle super_admin
        $user->syncRoles([$role->name]);

        $this->command->info('✅ Utilisateur super_admin créé ou mis à jour avec le rôle super_admin');
    }
}
