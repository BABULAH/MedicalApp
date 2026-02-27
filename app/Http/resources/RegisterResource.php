<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    protected string $token;
    protected string $type;
    protected int $expiresIn;

    public function __construct($user, $token, $type, $expiresIn)
    {
        parent::__construct($user);
        $this->token = $token;
        $this->type = $type;
        $this->expiresIn = $expiresIn;
    }

    public function toArray($request): array
    {
        return [
            'user' => [
                'id'             => $this->id,
                'full_name'      => $this->full_name,
                'first_name'     => $this->first_name,
                'last_name'      => $this->last_name,
                'email'          => $this->email,
                'phone'          => $this->phone,
                'gender'         => $this->gender,
                'date_of_birth'  => $this->date_of_birth,
                'address'        => $this->address,
                'roles'          => $this->getRoleNames(),
            ],

            'access_token' => $this->token,
            'token_type'   => $this->type,
            'expires_in'   => $this->expiresIn,
        ];
    }
}