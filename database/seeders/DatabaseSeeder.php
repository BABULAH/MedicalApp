<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1️⃣ Localités
            LocalitySeeder::class,

            // 2️⃣ Établissements
            EstablishmentSeeder::class,

            // 3️⃣ Spécialités
            SpecialitySeeder::class,

            // 4️⃣ Utilisateurs
            UserSeeder::class,

            // 5️⃣ Médecins
            DoctorSeeder::class,

            // 6️⃣ Disponibilités
            AvailabilitySeeder::class,

            // 7️⃣ Créneaux
            TimeSlotSeeder::class,

            // 8️⃣ Raisons de rendez-vous
            AppointmentReasonSeeder::class,

            // 9️⃣ Rendez-vous
            AppointmentSeeder::class,

            // 🔟 Avis / Reviews
            ReviewSeeder::class,
        ]);
    }
}
