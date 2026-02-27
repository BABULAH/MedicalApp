<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class ListAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('doctor');
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|in:attente,valide,annule',
            'doctor_id' => 'nullable|exists:doctors,id',
            'user_id' => 'nullable|exists:users,id',
            'date' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}       