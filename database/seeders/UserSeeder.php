<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Rôles (créés si inexistants)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $adminRole      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $doctorRole     = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $userRole       = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        /*
        |------------------------------------------------------------------
        | SUPER ADMIN
        |------------------------------------------------------------------
        */
        $superAdmin = User::create([
            'first_name'       => 'Super',
            'last_name'        => 'Admin',
            'email'            => 'superadmin@gmail.com',
            'phone'            => '777777777',
            'password'         => Hash::make('Passer123'),
            'locality_id'      => 1,
            'establishment_id' => null,
        ]);
        $superAdmin->assignRole($superAdminRole);

        /*
        |------------------------------------------------------------------
        | ADMIN ÉTABLISSEMENT
        |------------------------------------------------------------------
        */
        $admin = User::create([
            'first_name'       => 'Admin',
            'last_name'        => 'Hopital',
            'email'            => 'admin@gmail.com',
            'phone'            => '777777778',
            'password'         => Hash::make('Passer123'),
            'locality_id'      => 1,
            'establishment_id' => 1,
        ]);
        $admin->assignRole($adminRole);

        /*
        |------------------------------------------------------------------
        | MÉDECIN
        |------------------------------------------------------------------
        */
        $doctor = User::create([
            'first_name'       => 'Docteur',
            'last_name'        => 'Diallo',
            'email'            => 'doctor@gmail.com',
            'phone'            => '777777779',
            'password'         => Hash::make('Passer123'),
            'locality_id'      => 1,
            'establishment_id' => 1,
        ]);
        $doctor->assignRole($doctorRole);

        /*
        |------------------------------------------------------------------
        | UTILISATEUR SIMPLE (PATIENT)
        |------------------------------------------------------------------
        */
        $user = User::create([
            'first_name'       => 'Mor Talla',
            'last_name'        => 'Kebe',
            'email'            => 'user@gmail.com',
            'phone'            => '777777780',
            'password'         => Hash::make('Passer123'),
            'locality_id'      => 1,
            'establishment_id' => null,
        ]);
        $user->assignRole($userRole);
    }
}
