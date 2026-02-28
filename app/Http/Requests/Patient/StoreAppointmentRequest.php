<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctors,id'],
            'availability_id' => ['required', 'exists:availabilities,id'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
            'appointment_reason_id' => ['required', 'exists:appointment_reasons,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}