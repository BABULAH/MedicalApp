<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Pour chaque docteur existant
        Doctor::all()->each(function ($doctor) use ($days) {
            foreach ($days as $day) {
                Availability::create([
                    'doctor_id'       => $doctor->id,
                    'establishment_id'=> $doctor->establishment_id, // lien multi-tenant
                    'day_of_week'     => $day,
                    'start_time'      => '09:00',
                    'end_time'        => '13:00',
                    'is_active'       => true, // actif par défaut
                ]);
            }
        });
    }
}
