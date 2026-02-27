<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EstablishmentScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié '], 401);
        }

        // Vérifie que l'utilisateur appartient au bon établissement
        if ($request->has('establishment_id') &&
            $user->establishment_id != $request->establishment_id) {
            return response()->json([
                'message' => 'Accès interdit à cet établissement'
            ], 403);
        }

        return $next($request);
    }
}