<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventApiCache
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Completely prevent caching for API responses
        if ($request->is('api/*')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0, private', true);
            $response->headers->set('Pragma', 'no-cache', true);
            $response->headers->set('Expires', '0', true);
            $response->headers->set('X-Accel-Expires', '0', true);
        }

        return $response;
    }
}
