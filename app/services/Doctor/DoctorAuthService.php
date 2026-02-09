<?php

namespace App\Services\Doctor;

use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Exceptions\DoctorAuthException;


class DoctorAuthService
{
    public function attemptLogin(array $credentials): array
    {
        if (!$token = Auth::guard('api_doctor')->attempt($credentials)) {
            throw DoctorAuthException::notVerified();

        }

        /** @var Doctor $doctor */
        $doctor = Auth::guard('api_doctor')->user();

        $this->validateDoctorState($doctor);

        return [
            'token' => $token,
            'doctor' => $doctor,
        ];
    }

    protected function validateDoctorState(Doctor $doctor): void
    {
        if (!$doctor->is_verified) {
            throw new \Exception('DOCTOR_NOT_VERIFIED');
        }

        if ($doctor->status !== 'active') {
            throw new \Exception('DOCTOR_INACTIVE');
        }

        if (!$doctor->establishment_id) {
            throw new \Exception('NO_TENANT');
        }
    }
}
