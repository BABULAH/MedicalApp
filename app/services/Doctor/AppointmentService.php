<?php

namespace App\Services\Doctor;

use Illuminate\Support\Facades\{Auth, Log};
use App\Models\Appointment;


class AppointmentService
{
    public function list(array $filters)
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403, 'Aucun profil doctor trouvé.');
        }

        $query = Appointment::with([
            'user',
            'doctor.user',
            'establishment',
            'timeSlot',
            'reason'
        ])
        ->where('doctor_id', $doctor->id); // 🔒 Sécurité ici

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }



    // Méthode pour les rendez-vous en attente (indexWatcher)
    public function listAttente(array $filters = [])
    {
        $query = Appointment::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        // Autres filtres si besoin

        return $query->get();
    }
}