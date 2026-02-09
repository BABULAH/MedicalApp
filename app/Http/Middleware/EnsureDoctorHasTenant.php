<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDoctorHasTenant
{
    public function handle(Request $request, Closure $next)
    {
        $doctor = auth('api_doctor')->user();

        if (!$doctor || !$doctor->establishment_id) {
            return response()->json([
                'message' => 'Aucun établissement associé'
            ], 403);
        }

        return $next($request);
    }
}
