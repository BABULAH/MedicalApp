<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use App\Http\Resources\{AuthResource, RegisterResource};
use App\Services\RegisterService;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        private RegisterService $registerService
    ) {}

    // ──────────────────────────────────────────────
    //  REGISTER (Inscription Patient)
    // ──────────────────────────────────────────────
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->registerService->register(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie.',
            'data'    => new RegisterResource(
                $result['user'],
                $result['token'],
                'bearer',
                config('jwt.ttl') * 60
            ),
        ], 201);
    }


    // ──────────────────────────────────────────────
    //  LOGIN
    // ──────────────────────────────────────────────
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        $user = auth('api')->user();

        $user->load([
            'appointments.doctor.speciality',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'data' => new AuthResource(
                $user,
                $token,
                'bearer',
                config('jwt.ttl') * 60
            ),
        ]);
    }

    // ──────────────────────────────────────────────
    //  ME  (profil de l'utilisateur connecté)
    // ──────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();
        $user->load(['establishment', 'doctor.speciality']);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'             => $user->id,
                'full_name'      => $user->full_name,
                'first_name'     => $user->first_name,
                'last_name'      => $user->last_name,
                'email'          => $user->email,
                'phone'          => $user->phone,
                'gender'         => $user->gender,
                'date_of_birth'  => $user->date_of_birth,
                'address'        => $user->address,
                'roles'          => $user->getRoleNames(),
                'permissions'    => $user->getAllPermissions()->pluck('name'),
                'establishment'  => $user->establishment ? [
                    'id'   => $user->establishment->id,
                    'name' => $user->establishment->name,
                ] : null,
                'doctor_profile' => $user->doctor ? [
                    'id'                  => $user->doctor->id,
                    'speciality'          => $user->doctor->speciality?->name,
                    'registration_number' => $user->doctor->registration_number,
                    'is_verified'         => $user->doctor->is_verified,
                    'status'              => $user->doctor->status,
                ] : null,
            ],
        ]);
    }

    // ──────────────────────────────────────────────
    //  REFRESH TOKEN
    // ──────────────────────────────────────────────
    public function refresh(): JsonResponse
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            /** @var User $user */
            $user = auth('api')->user();
            $user->load(['establishment', 'doctor.speciality']);

            return response()->json([
                'success' => true,
                'message' => 'Token rafraîchi avec succès.',
                'data'    => new AuthResource($user, $newToken, 'bearer', config('jwt.ttl') * 60),
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Le token a expiré et ne peut plus être rafraîchi. Veuillez vous reconnecter.',
                'code'    => 'TOKEN_EXPIRED',
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de rafraîchir le token.',
                'code'    => 'TOKEN_INVALID',
            ], 401);
        }
    }

    // ──────────────────────────────────────────────
    //  LOGOUT
    // ────────────────────────────────────────────── 
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::parseToken()->invalidate();
        } catch (JWTException $e) {
            // Token déjà invalide ou absent : on laisse passer
            Log::warning('Logout with invalid token: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.',
        ]);
    }
}

