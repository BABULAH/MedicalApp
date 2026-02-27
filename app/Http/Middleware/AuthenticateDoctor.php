<?php

namespace App\Http\Middleware;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Log;
use Closure;
use Tymon\JWTAuth\Exceptions\{JWTException, TokenExpiredException, TokenInvalidException};
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateDoctor
{
    /**
     * Vérification complète de l'authentification JWT du docteur
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // ── 1. Vérifier la présence du token ──────────────────────────────
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token non fourni',
            ], 401);
        }

        // ── 2. Valider et décoder le token JWT ────────────────────────────
        try {
            // Injecter et authentifier le token
            $doctor = JWTAuth::setToken($token)->authenticate();

            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur introuvable',
                ], 401);
            }

            // ── 3. Vérifier que le compte est actif ───────────────────────
            if (isset($doctor->is_active) && !$doctor->is_active) {
                Log::warning('Tentative d\'accès avec un compte désactivé', [
                    'doctor_id' => $doctor->id,
                    'ip'        => $request->ip(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Compte désactivé',
                ], 403);
            }

            // ── 4. Vérifier la version JWT (protection logoutAllDevices) ──
            // Si jwt_version du token ne correspond pas à celle en base → rejeté
            $payload = JWTAuth::getPayload($token);
            $tokenJwtVersion = $payload->get('jwt_version') ?? 0;

            if ((int) $tokenJwtVersion !== (int) ($doctor->jwt_version ?? 0)) {
                Log::warning('Token JWT révoqué (version invalide)', [
                    'doctor_id'            => $doctor->id,
                    'token_jwt_version'    => $tokenJwtVersion,
                    'expected_jwt_version' => $doctor->jwt_version,
                    'ip'                   => $request->ip(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Session révoquée, veuillez vous reconnecter',
                ], 401);
            }

        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expiré, veuillez vous reconnecter',
                'code'    => 'TOKEN_EXPIRED',
            ], 401);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide',
                'code'    => 'TOKEN_INVALID',
            ], 401);

        } catch (JWTException $e) {
            Log::error('Erreur JWT dans le middleware', [
                'error' => $e->getMessage(),
                'ip'    => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur d\'authentification',
                'code'    => 'AUTH_ERROR',
            ], 500);
        }

        return $next($request);
    }
}