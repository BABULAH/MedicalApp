<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class CancelAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $appointment = $this->route('appointmentId') ;// Récupère l'ID du rendez-vous depuis la route
        return auth()->check() && auth()->user()->hasRole('patient');
    }

    public function rules(): array
    {
        return [];
    }
}