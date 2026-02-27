<?php

namespace App\Services\Patient;

use App\Models\Doctor;

class DoctorService
{
    public function list(array $filters)
    {
        $query = Doctor::query()
            ->active()
            ->verified()
            ->with(['user', 'speciality', 'establishment', 'locality']);

        if (!empty($filters['speciality_id'])) {
            $query->where('speciality_id', $filters['speciality_id']);
        }

        if (!empty($filters['establishment_id'])) {
            $query->ofEstablishment($filters['establishment_id']);
        }

        if (!empty($filters['locality_id'])) {
            $query->where('locality_id', $filters['locality_id']);
        }

        if (!empty($filters['search'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate(10);
    }

    public function show(Doctor $doctor): Doctor
    {
        return $doctor->load([
            'user',
            'speciality',
            'establishment',
            'locality',
            'reviews',
            'availabilities.timeSlots'
        ]);
    }
}