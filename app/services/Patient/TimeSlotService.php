<?php

namespace App\Services\Patient;

use App\Models\TimeSlot;

class TimeSlotService
{
    public function list(array $filters)
    {
        $query = TimeSlot::query()->with(['availability', 'availability.doctor']);

        // Filtrer par doctor_id via la table availabilities
        if (!empty($filters['doctor_id'])) {
            $query->whereHas('availability', function ($q) use ($filters) {
                $q->where('doctor_id', $filters['doctor_id']);
            });
        }

        // Filtrer par availability_id
        if (!empty($filters['availability_id'])) {
            $query->where('availability_id', $filters['availability_id']);
        }

        return $query->orderBy('start_time')->get();
    }


    /**
     * Lister les créneaux d'une disponibilité spécifique
     */
    public function listByAvailability(int $availabilityId)
    {
        return TimeSlot::with(['availability', 'availability.doctor'])
            ->where('availability_id', $availabilityId)
            ->orderBy('start_time')
            ->get();
    }
}