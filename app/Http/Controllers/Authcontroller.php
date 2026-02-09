<?php

namespace App\Http\Controllers\api\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\LoginRequest;
use App\Services\Doctor\DoctorAuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\DoctorAuthException;
use App\Http\Resources\Doctor\DoctorResource;

class AuthController extends Controller
{
    public function __construct(
        protected DoctorAuthService $authService
    ) {}

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->attemptLogin($request->validated());

            return response()->json([
                'access_token' => $result['token'],
                'token_type' => 'Bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'doctor' => new DoctorResource($result['doctor']),
            ]);
        } catch (DoctorAuthException $e) {
            return $this->handleAuthError($e);
        }

    }

    protected function handleAuthError(\Exception $e)
    {
        return match ($e->getMessage()) {
            'DOCTOR_NOT_VERIFIED' =>
                response()->json(['message' => 'Compte non vérifié'], 403),

            'DOCTOR_INACTIVE' =>
                response()->json(['message' => 'Compte inactif'], 403),

            'NO_TENANT' =>
                response()->json(['message' => 'Aucun établissement associé'], 403),

            default =>
                response()->json(['message' => 'Identifiants invalides'], 401),
        };
    }
    

    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

}
