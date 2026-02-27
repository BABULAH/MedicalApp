<?php

namespace App\Services\Doctor;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService 
{
    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    /**
     * Tente d'authentifier l'utilisateur et retourne un token Sanctum.
     *
     * @throws ValidationException
     */
    public function login(array $credentials, string $deviceName = 'api'): array
    {
        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        $this->ensureUserIsActive($user);

        // Révoque les anciens tokens du même device pour éviter l'accumulation
        $user->tokens()->where('name', $deviceName)->delete();

        // Définit les abilities (permissions) du token selon le rôle
        $abilities = $this->resolveAbilities($user);

        $token = $user->createToken($deviceName, $abilities)->plainTextToken;

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

    /**
     * Révoque le token courant (logout simple).
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Révoque tous les tokens de l'utilisateur (logout de tous les appareils).
     */
    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Refresh token
    |--------------------------------------------------------------------------
    */

    /**
     * Régénère un nouveau token en révoquant l'ancien.
     */
    public function refreshToken(User $user, string $deviceName = 'api'): string
    {
        $user->currentAccessToken()->delete();

        $abilities = $this->resolveAbilities($user);

        return $user->createToken($deviceName, $abilities)->plainTextToken;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers privés
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie que le compte est actif (à adapter selon ta logique métier).
     *
     * @throws ValidationException
     */
    private function ensureUserIsActive(User $user): void
    {
        // Si tu as un champ status sur User, décommente :
        // if ($user->status !== 'active') {
        //     throw ValidationException::withMessages([
        //         'email' => ['Votre compte est désactivé. Contactez l\'administrateur.'],
        //     ]);
        // }

        // Si l'user est un doctor, vérifie aussi son statut
        if ($user->hasRole('doctor') && $user->doctor) {
            if ($user->doctor->status !== 'active') {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte médecin est en attente de validation.'],
                ]);
            }
        }
    }

    /**
     * Résout les abilities Sanctum en fonction du rôle Spatie.
     * Ces abilities peuvent être vérifiées via $request->user()->tokenCan('...')
     */
    private function resolveAbilities(User $user): array
    {
        $role = $user->getRoleNames()->first();

        return match ($role) {
            'superadmin' => ['*'],                                         // Accès total
            'admin'      => ['read', 'write', 'delete', 'manage-staff'],   // Gestion établissement
            'doctor'     => ['read', 'write', 'manage-appointments'],      // Médecin
            'patient'    => ['read', 'write-own'],                         // Patient
            default      => ['read'],
        };
    }
}
