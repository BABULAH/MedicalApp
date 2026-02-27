<?php

namespace App\Services\Patient;

use Illuminate\Validation\ValidationException;
use App\Models\{Appointment, Availability, Doctor, TimeSlot};

class AppointmentService
{
    public function create(array $data): Appointment
    {
        $patient = auth()->user();

        // 1️⃣ Vérifier que le médecin existe
        $doctor = Doctor::findOrFail($data['doctor_id']);

        // 2️⃣ Vérifier que la disponibilité appartient au médecin
        $availability = Availability::where('id', $data['availability_id'])
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$availability) {
            throw ValidationException::withMessages([
                'availability_id' => 'Cette disponibilité n\'appartient pas à ce médecin.'
            ]);
        }

        // 3️⃣ Vérifier que le créneau appartient à la disponibilité
        $timeSlot = TimeSlot::where('id', $data['time_slot_id'])
            ->where('availability_id', $availability->id)
            ->first();

        if (!$timeSlot) {
            throw ValidationException::withMessages([
                'time_slot_id' => 'Ce créneau n\'appartient pas à cette disponibilité.'
            ]);
        }

        // 4️⃣ Vérifier que le créneau n'est pas déjà pris
        $alreadyBooked = Appointment::where('time_slot_id', $timeSlot->id)
            ->whereIn('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_APPROVED
            ])
            ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'time_slot_id' => 'Ce créneau est déjà réservé.'
            ]);
        }

        // 5️⃣ Création du rendez-vous
        return Appointment::create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'establishment_id' => $doctor->establishment_id,
            'date' => $availability->date,
            'time_slot_id' => $timeSlot->id,
            'appointment_reason_id' => $data['appointment_reason_id'],
            'status' => Appointment::STATUS_PENDING,
        ]);
    }
}