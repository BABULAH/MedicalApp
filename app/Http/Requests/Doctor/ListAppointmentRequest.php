<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class ListAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // On autorise uniquement les médecins connectés
        return auth()->check() && auth()->user()->hasRole('doctor');
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:attente,valide,annule',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'patient_name' => 'nullable|string|max:255',
        ];
    }
}