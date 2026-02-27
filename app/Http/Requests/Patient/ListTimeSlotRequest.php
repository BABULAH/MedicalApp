<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class ListTimeSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
    }   

    

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $doctorId = $this->doctor_id ?? $this->route('doctor_id');
            $availabilityId = $this->availability_id ?? $this->route('availability_id');

            if (!$doctorId && !$availabilityId) {
                $validator->errors()->add(
                    'doctor_id',
                    'Vous devez fournir doctor_id ou availability_id.'
                );
            }
        });
    }


        public function rules(): array
    {
        return [
            'doctor_id' => 'nullable|exists:users,id',
            'availability_id' => 'nullable|exists:availabilities,id',
        ];
    }
}