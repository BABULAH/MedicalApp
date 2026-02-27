<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'address' => $this->address,
            'establishment_id' => $this->establishment_id,
            'locality_id' => $this->locality_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}