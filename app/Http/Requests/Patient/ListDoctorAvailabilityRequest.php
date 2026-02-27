<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class ListDoctorAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les patients authentifiés peuvent accéder
        return auth()->check() && auth()->user()->hasRole('patient');
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id', // vérifie que le médecin existe
        ];
    }

    public function validationData()
    {
        // Prend le doctor_id depuis l'URL si pas dans le body
        return array_merge($this->all(), [
            'doctor_id' => $this->route('doctor_id')
        ]);
    }
}