<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'registration_number' => $this->registration_number,
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'consultation_price' => $this->consultation_price,
            'emergency_price' => $this->emergency_price,
            'is_verified' => $this->is_verified,
            'status' => $this->status,

            'speciality' => $this->speciality?->name,

            'establishment' => [
                'id' => $this->establishment?->id,
                'name' => $this->establishment?->name,
            ],

            'locality' => $this->locality?->name,

            'reviews_count' => $this->reviews?->count(),

            'availabilities' => $this->whenLoaded('availabilities', function () {
                return $this->availabilities->map(function ($availability) {
                    return [
                        'id' => $availability->id,
                        'date' => $availability->date,
                        'time_slots' => $availability->timeSlots->map(function ($slot) {
                            return [
                                'id' => $slot->id,
                                'start_time' => $slot->start_time,
                                'end_time' => $slot->end_time,
                            ];
                        })
                    ];
                });
            }),
        ];
    }
}