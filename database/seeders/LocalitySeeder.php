<?php

namespace Database\Seeders;

use App\Models\Locality;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LocalitySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $localities = [
            ['name' => 'Hôpital Principal de Dakar', 'region' => 'Dakar'],
            ['name' => 'Hôpital Fann', 'region' => 'Dakar'],
            ['name' => 'Clinique Pasteur', 'region' => 'Dakar'],
            ['name' => 'Clinique Madeleine', 'region' => 'Dakar'],
            ['name' => 'Clinique Yoff', 'region' => 'Dakar'],
        ];

        foreach ($localities as $locality) {
            Locality::firstOrCreate(
                ['name' => $locality['name']],
                [
                    'region'    => $locality['region'],
                    'latitude'  => $faker->latitude(14.65, 14.80),
                    'longitude' => $faker->longitude(-17.50, -17.40),
                ]
            );
        }
    }
}
