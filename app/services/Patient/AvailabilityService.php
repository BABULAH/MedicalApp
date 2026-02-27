<?php

namespace App\Services\Patient;

use App\Models\Availability;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Récupère les disponibilités d'un médecin
     *
     * @param int $doctorId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDoctorAvailabilities(int $doctorId)
    {
        return Availability::where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }
}