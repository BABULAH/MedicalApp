<?php

namespace App\Services\Doctor;

use Illuminate\Support\Facades\{Auth, Log};
use App\Models\Appointment;
use Illuminate\Support\Collection;


class AppointmentService
{
    public function list(array $filters = [])
    {
        $doctor = auth()->user()->doctor;

        $query = Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->with([
                'doctor.user',
                'user',
                'availability',
                'timeSlot',
                'reason'
            ]);

        // 🔎 Filtre par statut
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 📅 Filtre par date
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        // 👤 Filtre par nom du patient
        if (!empty($filters['patient_name'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['patient_name'] . '%')
                ->orWhere('last_name', 'like', '%' . $filters['patient_name'] . '%');
            });
        }

        return $query->orderBy('date', 'desc')->paginate(10);
    }
}