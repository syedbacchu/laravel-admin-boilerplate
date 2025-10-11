<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'This action requires administrator privileges.');
        }

        return $next($request);
    }
}
