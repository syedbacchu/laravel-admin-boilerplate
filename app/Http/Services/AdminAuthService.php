<?php

namespace App\Http\Services;

use App\Enums\StatusEnum;
use App\Enums\UserRole;
use App\Models\User;
use App\Http\Services\AdminActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
        $loginInput = $request->input('login'); // can be username, email, or phone
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Rate limit key based on user identifier + IP
        $key = Str::lower($loginInput) . '|' . $request->ip();

        // ✅ Rate limit check: max 4 attempts per 20 minutes
        if (RateLimiter::tooManyAttempts($key, 4)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'login' => ["Too many login attempts. Try again in " . ceil($seconds / 60) . " minutes."],
            ]);
        }

        // ✅ Find user by email, username, or phone
        $user = User::query()
            ->where(function ($q) use ($loginInput) {
                $q->where('email', $loginInput)
                    ->orWhere('username', $loginInput)
                    ->orWhere('phone', $loginInput);
            })
            ->whereIn('role_module', [UserRole::SUPER_ADMIN_ROLE, UserRole::ADMIN_ROLE])
            ->where('status', StatusEnum::ACTIVE)
            ->first();

        // ✅ Validate user and password
        if (!$user || !Hash::check($password, $user->password)) {
            RateLimiter::hit($key, 60 * 20); // block for 20 minutes after 4 fails

            $this->activityLogger->logFailedLogin($loginInput, [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'reason' => 'invalid_credentials',
            ]);

            throw ValidationException::withMessages([
                'login' => ['Invalid credentials or insufficient privileges.'],
            ]);
        }

        // ✅ Clear rate limit on success
        RateLimiter::clear($key);

        // ✅ Extra security check
        if (!$user->email_verified_at) {
            throw ValidationException::withMessages([
                'login' => ['Please verify your email address before logging in.'],
            ]);
        }

        // ✅ Login user
        Auth::login($user, $remember);

        // ✅ Update login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // ✅ Log success
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
            $sessionDuration = $user->last_login_at
                ? now()->diffInMinutes($user->last_login_at)
                : 0;

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
