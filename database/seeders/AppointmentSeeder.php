<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Models\TimeSlot;
use App\Models\AppointmentReason;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $doctors = Doctor::all();
        $timeSlots = TimeSlot::all();
        $reasons = AppointmentReason::all();

        if ($users->isEmpty() || $doctors->isEmpty() || $timeSlots->isEmpty() || $reasons->isEmpty()) {
            $this->command->info('Veuillez seed les utilisateurs, médecins, créneaux et raisons avant.');
            return;
        }

        foreach (range(1, 10) as $i) { // créer 10 rendez-vous aléatoires
            $doctor = $doctors->random();
            $user = $users->random();
            $doctorAvailabilityIds = $doctor->availabilities->pluck('id')->toArray();

            // récupérer les créneaux liés aux disponibilités du docteur
            $doctorTimeSlots = $timeSlots->whereIn('availability_id', $doctorAvailabilityIds);

            if ($doctorTimeSlots->isEmpty()) {
                continue; // pas de créneaux pour ce docteur
            }

            $timeSlot = $doctorTimeSlots->random();
            $reason = $reasons->random();

            Appointment::create([
                'user_id'               => $user->id,
                'doctor_id'             => $doctor->id,
                'time_slot_id'          => $timeSlot->id,
                'appointment_reason_id' => $reason->id,
                'date'                  => now()->addDays(rand(1, 30))->toDateString(),
                'status'                => collect(['en_attente', 'accepte', 'refuse', 'annule'])->random(),
                'establishment_id'      => $doctor->establishment_id, // multi-tenant
            ]);
        }
    }
}
