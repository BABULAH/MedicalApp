<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'heure de debut' => $this->start_time,
            'heure de fin' => $this->end_time,
            'est reservé' => (bool) $this->is_booked,
            'disponibilite_id' => $this->availability_id,
            'jour' => $this->availability ? $this->availability->day_of_week : null, // <-- jour de la disponibilité
            'Docteur' => $this->availability && $this->availability->doctor ? [
                'id' => $this->availability->doctor->id,
                'nom' => $this->availability->doctor->first_name . ' ' . $this->availability->doctor->last_name,
            ] : null,
        ];
    }
}