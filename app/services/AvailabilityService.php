<?php

namespace App\Services;

use App\Models\Availability;
use Illuminate\Validation\ValidationException;

class AvailabilityService
{
    public static function checkOverlap(
        int $doctorId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $ignoreId = null
    ): void {
        $query = Availability::query()
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            // chevauchement STRICT (même 1 minute)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            });

        // Exclure l’enregistrement en cours lors d’un update
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'start_time' => 'Cette disponibilité chevauche une autre disponibilité existante pour ce médecin.',
            ]);
        }
    }
}
