<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Establishment;
use App\Models\Locality;
use Faker\Factory as Faker;

class EstablishmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $localities = Locality::all();

        $establishments = [
            ['name' => 'Hôpital Principal de Dakar', 'type' => 'hopital', 'address' => 'Dakar, Sénégal'],
            ['name' => 'Hôpital Fann', 'type' => 'hopital', 'address' => 'Dakar, Sénégal'],
            ['name' => 'Clinique Pasteur', 'type' => 'clinique', 'address' => 'Dakar, Sénégal'],
            ['name' => 'Clinique Madeleine', 'type' => 'clinique', 'address' => 'Dakar, Sénégal'],
            ['name' => 'Clinique Yoff', 'type' => 'clinique', 'address' => 'Yoff, Sénégal'],
        ];

        foreach ($establishments as $data) {
            $locality = $localities->random();

            Establishment::create([
                'name'        => $data['name'],
                'type'        => $data['type'],
                'address'     => $data['address'],
                'locality_id' => $locality->id,
                'latitude'    => $faker->latitude($locality->latitude ?? null, $locality->latitude ?? null),
                'longitude'   => $faker->longitude($locality->longitude ?? null, $locality->longitude ?? null),
                'phone'       => $faker->phoneNumber,
                'email'       => $faker->unique()->safeEmail,
            ]);
        }
    }
}
