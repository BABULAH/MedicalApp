<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class EstablishmentScopeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Si ce n'est pas un super-admin, on applique le filtre establishment_id
            if ($user->role !== 'super_admin' && $user->establishment_id) {
                $this->applyGlobalScopes($user->establishment_id);
            }
        }

        return $next($request);
    }

    /**
     * Applique le Global Scope sur tous les modèles multi-tenant.
     */
    protected function applyGlobalScopes(int $establishmentId)
    {
        $models = [
            \App\Models\Appointment::class,
            \App\Models\Doctor::class,
            \App\Models\User::class,
            \App\Models\Availability::class,
            \App\Models\TimeSlot::class,
            \App\Models\Review::class,
            \App\Models\Notification::class
        ];

        foreach ($models as $model) {
            $model::addGlobalScope('establishment_id', function (Builder $builder) use ($establishmentId) {
                // Vérifie que le modèle possède bien le champ establishment_id
                if (in_array('establishment_id', $builder->getModel()->getFillable())) {
                    $builder->where('establishment_id', $establishmentId);
                }
            });
        }
    }
}
