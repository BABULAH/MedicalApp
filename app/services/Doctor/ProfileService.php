<?php

namespace App\Services\Doctor;

use Illuminate\Support\Facades\{Hash, Storage};
use App\Models\Doctor;

class ProfileService
{
   public function getProfile(): Doctor
    {
        return Doctor::with(['user', 'speciality', 'establishment', 'locality'])
            ->where('user_id', auth()->id()) // ✅ correction
            ->firstOrFail();
    }



public function updateProfile(Doctor $doctor, array $data): Doctor
{
    // Mise à jour photo
    if (isset($data['photo'])) {
        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }
        $doctor->photo = $data['photo'];
    }

    // Séparer données user / doctor
    $userFields = ['first_name', 'last_name', 'email', 'phone', 'password'];

    $userData = array_intersect_key($data, array_flip($userFields));
    $doctorData = array_diff_key($data, $userData);

    // Hash password si présent
    if (isset($userData['password'])) {
        $userData['password'] = Hash::make($userData['password']);
    }

    // Update seulement si data existe
    if (!empty($userData)) {
        $doctor->user()->update($userData);
    }

    if (!empty($doctorData)) {
        $doctor->update($doctorData);
    }

    return $this->getProfile();
}
}
