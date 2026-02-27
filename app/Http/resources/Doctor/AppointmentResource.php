<?php

namespace App\Http\Resources\Doctor;

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
            'doctor_comment' => $this->doctor_comment,

            'patient' => [
                'id' => $this->user?->id,
                'name' => $this->user?->first_name . ' ' . $this->user?->last_name,
                'email' => $this->user?->email,
            ],

            'doctor' => [
                'id' => $this->doctor?->id,
                'name' => $this->doctor?->user?->first_name . ' ' . $this->doctor?->user?->last_name,
                'speciality_id' => $this->doctor?->speciality_name,
            ],

            'establishment' => [
                'id' => $this->establishment?->id,
                'name' => $this->establishment?->name,
            ],

            'availibility' => [
                'id' => $this->availability?->id,
                'date' => $this->availability?->day_of_week,
            ],

            'time_slot' => [
                'id' => $this->timeSlot?->id,
                'start_time' => $this->timeSlot?->start_time,
                'end_time' => $this->timeSlot?->end_time,
            ],

            'reason' => [
                'id' => $this->reason?->id,
                'label' => $this->reason?->label,
            ],

            'created_at' => $this->created_at,
        ];
    }
}