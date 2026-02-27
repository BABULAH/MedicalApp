<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // inscription publique
    }

    public function rules(): array
    {
        return [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:6|confirmed',
            'phone'          => 'nullable|string|max:20',
            'gender'         => 'nullable|in:male,female,other',
            'date_of_birth'  => 'nullable|date|before:today',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd’hui.',
        ];
    }
}