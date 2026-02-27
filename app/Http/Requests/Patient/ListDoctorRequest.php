<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class ListDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
    }

    public function rules(): array
    {
        return [
            'speciality_id' => ['nullable', 'exists:specialities,id'],
            'establishment_id' => ['nullable', 'exists:establishments,id'],
            'locality_id' => ['nullable', 'exists:localities,id'],
            'search' => ['nullable', 'string'],
        ];
    }
}