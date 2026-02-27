<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash};
use App\Models\User;

class RegisterService
{
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'email'         => $data['email'],
                'password' => $data['password'],
                'phone'         => $data['phone'] ?? null,
                'gender'        => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'address'       => $data['address'] ?? null,
            ]);

            // Attribution du rôle patient
            $user->assignRole('patient');

            // Génération token JWT
            $token = auth('api')->login($user);

            return [
                'user'  => $user,
                'token' => $token,
            ];
        });
    }
}