<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    private string $token;
    private string $tokenType;

    public function __construct($resource, string $token, string $tokenType = 'Bearer')
    {
        parent::__construct($resource);
        $this->token     = $token;
        $this->tokenType = $tokenType;
    }

    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = $this->resource;

        return [
            'token'      => $this->token,
            'token_type' => $this->tokenType,
            'user'       => [
                'id'               => $user->id,
                'full_name'        => $user->full_name,
                'first_name'       => $user->first_name,
                'last_name'        => $user->last_name,
                'email'            => $user->email,
                'phone'            => $user->phone,
                'gender'           => $user->gender,
                'date_of_birth'    => $user->date_of_birth?->format('Y-m-d'),
                'address'          => $user->address,
                'establishment_id' => $user->establishment_id,
                'role'             => $user->getRoleNames()->first(),
                'permissions'      => $user->getAllPermissions()->pluck('name'),
                // Infos doctor si applicable
                'doctor'           => $this->when(
                    $user->hasRole('doctor') && $user->doctor,
                    fn () => [
                        'id'                  => $user->doctor->id,
                        'speciality'          => $user->doctor->speciality?->name,
                        'is_verified'         => $user->doctor->is_verified,
                        'status'              => $user->doctor->status,
                        'consultation_price'  => $user->doctor->consultation_price,
                        
                    ]
                ),
            ],
        ];
    }
}
