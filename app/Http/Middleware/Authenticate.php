<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
protected function redirectTo($request)
{
    // Si c'est une requête API, retourne null pour renvoyer un 401 JSON
    if ($request->expectsJson() || $request->is('api/*')) {
        return null;
    }

    // Sinon, redirige vers login pour le web
    return route('login');
}


}
