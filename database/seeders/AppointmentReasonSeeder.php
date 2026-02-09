<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentReason;

class AppointmentReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            ['name' => 'Consultation générale', 'description' => 'Consultation médicale standard pour tout type de symptôme.'],
            ['name' => 'Urgence', 'description' => 'Problème de santé nécessitant une prise en charge immédiate.'],
            ['name' => 'Suivi médical', 'description' => 'Rendez-vous pour suivi d’une maladie ou traitement en cours.'],
            ['name' => 'Résultats d’analyse', 'description' => 'Discussion des résultats d’examens ou analyses.'],
            ['name' => 'Contrôle', 'description' => 'Rendez-vous de contrôle après traitement ou intervention.'],
            ['name' => 'Visite de routine', 'description' => 'Consultation préventive ou annuelle.'],
            ['name' => 'Vaccination', 'description' => 'Rendez-vous pour administration d’un vaccin.'],
            ['name' => 'Conseil nutritionnel', 'description' => 'Consultation pour conseils diététiques et nutrition.'],
        ];

        foreach ($reasons as $reason) {
            // Création pour chaque établissement existant + global
            foreach ([null, 1, 2, 3] as $establishmentId) {
                AppointmentReason::create([
                    'name'             => $reason['name'],
                    'description'      => $reason['description'],
                    'establishment_id' => $establishmentId, // null = global, sinon rattaché à un établissement
                ]);
            }
        }
    }
}
