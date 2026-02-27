<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService 
{
    /*
    |--------------------------------------------------------------------------
    | Login Patient
    |--------------------------------------------------------------------------
    */

    public function login(array $credentials, string $deviceName = 'patient-api'): array
    {
        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ])) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // ✅ Vérifie que c'est bien un patient
        if (! $user->hasRole('patient')) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => ['Accès réservé aux patients !!.'],
            ]);
        }

        $this->ensureUserIsActive($user);

        // Charger les relations utiles au patient
        $user->load([
            'appointments.doctor.speciality',
        ]);

        // Supprimer anciens tokens du même device
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken(
            $deviceName,
            $this->resolveAbilities()
        )->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
        
    }

    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Refresh Token
    |--------------------------------------------------------------------------
    */

    public function refreshToken(User $user, string $deviceName = 'patient-api'): string
    {
        $user->currentAccessToken()?->delete();

        return $user->createToken(
            $deviceName,
            $this->resolveAbilities()
        )->plainTextToken;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function ensureUserIsActive(User $user): void
    {
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est désactivé.'],
            ]);
        }
    }

    /**
     * Abilities spécifiques patient
     */
    private function resolveAbilities(): array
    {
        return [
            'read',
            'write-own',       // Modifier ses infos
            'book-appointment' // Prendre rendez-vous
        ];
    }
}