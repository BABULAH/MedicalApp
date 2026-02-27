<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time->format('H:i'),
            'end_time' => $this->end_time->format('H:i'),
            'establishment_id' => $this->establishment_id,
        ];
    }
}