<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6'],
            // Nom du device pour Sanctum (mobile, web, etc.)
            'device_name' => ['sometimes', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'L\'adresse email est obligatoire.',
            'email.email'       => 'L\'adresse email n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Retourne une réponse JSON propre en cas d'échec de validation
    |--------------------------------------------------------------------------
    */

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
