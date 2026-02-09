<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    public function run(): void
    {
        $specialities = [
            'Médecine générale',
            'Cardiologie',
            'Pédiatrie',
            'Dermatologie',
            'Gynécologie',
            'Orthopédie',
            'Neurologie',
            'Ophtalmologie',
        ];

        foreach (Establishment::all() as $establishment) {
            foreach ($specialities as $name) {
                Speciality::firstOrCreate(
                    [
                        'establishment_id' => $establishment->id,
                        'name' => $name,
                    ],
                    [
                        'description' => fake()->sentence(),
                    ]
                );
            }
        }
    }
}
