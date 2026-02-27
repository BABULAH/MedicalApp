<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('doctor')->check();
    }

    public function rules(): array
    {
        return [];
    }
}
