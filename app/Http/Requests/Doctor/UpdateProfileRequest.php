<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
public function authorize(): bool
{
    $user = $this->user(); // utilisateur connecté via guard actuel
    return $user && $user->hasRole('doctor'); // Spatie role check
}

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . auth()->id(),
            'password' => 'sometimes|string|min:6|confirmed',
            'phone' => 'sometimes|string|max:20',
            'bio' => 'sometimes|string|max:2000',
            'experience_years' => 'sometimes|integer|min:0|max:100',
            'address' => 'sometimes|string|max:500',
            'locality_id' => 'sometimes|exists:localities,id',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            
        ];
    }
}