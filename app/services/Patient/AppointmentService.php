<?php

namespace App\Services\Patient;

use Illuminate\Validation\ValidationException;
use App\Models\{Appointment, Availability, Doctor, TimeSlot};
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentService
{
    public function create(array $data): Appointment
    {
        $patient = auth()->user();

        // ✅ Récupérer la date envoyée par le patient
        $date = Carbon::parse($data['appointment_date']);

        // ❗ Empêcher réservation dans le passé
        if ($date->isPast()) {
            throw ValidationException::withMessages([
                'appointment_date' => 'Vous ne pouvez pas réserver dans le passé.'
            ]);
        }

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

        // 3️⃣ Vérifier que la date correspond au bon jour de la semaine
        if (strtolower($date->format('l')) !== strtolower($availability->day_of_week)) {
            throw ValidationException::withMessages([
                'appointment_date' => 'La date choisie ne correspond pas au jour de disponibilité.'
            ]);
        }

        // 4️⃣ Vérifier que le créneau appartient à la disponibilité
        $timeSlot = TimeSlot::where('id', $data['time_slot_id'])
            ->where('availability_id', $availability->id)
            ->first();

        if (!$timeSlot) {
            throw ValidationException::withMessages([
                'time_slot_id' => 'Ce créneau n\'appartient pas à cette disponibilité.'
            ]);
        }

        // 5️⃣ Vérifier que le créneau n'est pas déjà pris POUR CETTE DATE
        $alreadyBooked = Appointment::where('time_slot_id', $timeSlot->id)
            ->where('date', $date->toDateString())
            ->whereIn('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_APPROVED
            ])
            ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'time_slot_id' => 'Ce créneau est déjà réservé pour cette date.'
            ]);
        }

        // 6️⃣ Création du rendez-vous
        return Appointment::create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'establishment_id' => $doctor->establishment_id,
            'date' => $date->toDateString(), //✅VRAIE date du rendez-vous
            'availability_id' => $availability->id,
            'time_slot_id' => $timeSlot->id,
            'appointment_reason_id' => $data['appointment_reason_id'],
            'status' => Appointment::STATUS_PENDING,
        ]);
    }


    public function cancel(Appointment $appointment): Appointment
    {
        if ($appointment->status === Appointment::STATUS_CANCELLED) {
            throw ValidationException::withMessages([
                'appointment' => 'Ce rendez-vous est déjà annulé.'
            ]);
        }

        if ($appointment->status === Appointment::STATUS_APPROVED) {
            throw ValidationException::withMessages([
                'appointment' => 'Vous ne pouvez pas annuler un rendez-vous déjà validé.'
            ]);
        }

        if (now()->greaterThan($appointment->date)) {
            throw ValidationException::withMessages([
                'appointment' => 'Impossible d’annuler un rendez-vous passé.'
            ]);
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED
        ]);

        return $appointment; // ⚠️ retourne l’objet Appointment
    }



    /**
     * Liste tous les rendez-vous d'un médecin avec filtres
     */
    public function list(array $filters = []): Collection
    {
        $query = Appointment::query()
            ->where('doctor_id', auth()->id()); // Le médecin connecté

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Liste uniquement les rendez-vous en attente
     */
    public function listAttente(array $filters = []): Collection
    {
        $filters['status'] = 'attente';
        return $this->list($filters);
    }

    /**
     * Liste uniquement les rendez-vous validés
     */
    public function listValide(array $filters = []): Collection
    {
        $filters['status'] = 'valide';
        return $this->list($filters);
    }

    /**
     * Liste uniquement les rendez-vous annulés
     */
    public function listAnnule(array $filters = []): Collection
    {
        $filters['status'] = 'annule';
        return $this->list($filters);
    }
}