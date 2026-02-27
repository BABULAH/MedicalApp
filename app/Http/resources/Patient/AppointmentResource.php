<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'status' => $this->status,
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->user->first_name . ' ' . $this->doctor->user->last_name,
            ],
            'patient' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
            ],
            'time_slot' => [
                'id' => $this->timeSlot->id,
                'start_time' => $this->timeSlot->start_time,
                'end_time' => $this->timeSlot->end_time,
            ],
            'reason' => $this->reason->name,
            'establishment_id' => $this->establishment_id,
            'created_at' => $this->created_at,
        ];
    }
}