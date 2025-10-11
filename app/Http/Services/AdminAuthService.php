<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Services\AdminActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AdminAuthService
{
    protected AdminActivityLogger $activityLogger;

    public function __construct(AdminActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    public function authenticate(Request $request): bool
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Find user with admin/staff role
        $user = User::where('email', $credentials['email'])
            ->whereIn('user_type', [User::TYPE_ADMIN, User::TYPE_STAFF])
            ->where('banned', false)
            ->where('active_status', true)
            ->where('verification_status', true)
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($request->ip());

            $this->activityLogger->logFailedLogin($credentials['email'], [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'reason' => 'invalid_credentials',
            ]);

            throw ValidationException::withMessages([
                'email' => ['Invalid credentials or insufficient privileges.'],
            ]);
        }

        // Additional security checks
        if (!$user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email address before logging in.'],
            ]);
        }

        // Login the user
        Auth::login($user, $remember);

        // Clear rate limiting
        RateLimiter::clear($request->ip());

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Log successful login
        $this->activityLogger->log($user, 'admin_login', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'remember' => $remember,
        ]);

        return true;
    }

    public function logout(Request $request): void
    {
        $user = Auth::user();

        if ($user) {
            // Calculate session duration
            $sessionDuration = $user->last_login_at ?
                now()->diffInMinutes($user->last_login_at) : 0;

            // Log logout
            $this->activityLogger->log($user, 'admin_logout', [
                'ip' => $request->ip(),
                'session_duration_minutes' => $sessionDuration,
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
