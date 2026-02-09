<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Establishment;
use App\Models\Speciality;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $establishments = Establishment::with('specialities')->get();

        if ($establishments->isEmpty()) {
            $this->command->error('Aucun établissement trouvé');
            return;
        }

        foreach ($establishments as $establishment) {

            // Sécurité : l’établissement doit avoir des spécialités
            if ($establishment->specialities->isEmpty()) {
                continue;
            }

            // Créer 3 médecins par établissement
            for ($i = 0; $i < 3; $i++) {

                // 1️⃣ User
                $user = User::create([
                    'first_name'       => $faker->firstName,
                    'last_name'        => $faker->lastName,
                    'email'            => $faker->unique()->safeEmail,
                    'phone'            => $faker->phoneNumber,
                    'password'         => Hash::make('password'),
                    'role'             => 'doctor',
                    'establishment_id' => $establishment->id,
                ]);

                // 2️⃣ Spécialité DU MÊME établissement
                $speciality = $establishment->specialities->random();

                // 3️⃣ Doctor
                Doctor::create([
                    'user_id'            => $user->id,
                    'establishment_id'   => $establishment->id,
                    'speciality_id'      => $speciality->id,
                    'registration_number'=> $faker->bothify('????#####'),
                    'bio'                => $faker->paragraph,
                    'experience_years'   => rand(1, 30),
                    'phone'              => $faker->phoneNumber,
                    'email'              => $faker->unique()->safeEmail,
                    'address'            => $faker->address,
                    'locality_id'        => $establishment->locality_id,
                    'latitude'           => $faker->latitude,
                    'longitude'          => $faker->longitude,
                    'consultation_price' => rand(5000, 15000),
                    'emergency_price'    => rand(10000, 25000),
                    'is_verified'        => true,
                    'status'             => 'actif',
                ]);
            }
        }
    }
}
