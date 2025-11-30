<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Services\AdminAuthService;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected AdminAuthService $authService;

    public function __construct(AdminAuthService $authService)
    {
        $this->middleware('guest')->except('logout');
        $this->authService = $authService;
    }

    /**
     * Display the admin login form.
     */
    public function showLogin(): View
    {
        $data['pageTitle'] = __('Admin Login');
        return ResponseService::send([
            'data' => $data,
        ], view: viewss('auth','login'));
    }

    /**
     * Handle admin login attempt.
     */
    public function login(AdminLoginRequest $request): RedirectResponse
    {
        try {
            $request->ensureIsNotRateLimited();
            $this->authService->authenticate($request);
            $request->session()->regenerate();
            $user = Auth::user();

            return redirect()->intended(route('dashboard'))
                ->with('success', "Welcome back, {$user->name}!");

        } catch (\Exception $e) {
            return back()
                ->withErrors(['login' => $e->getMessage()])
                ->withInput($request->except('password'));
        }
    }

    public  function forgotPassword() {
        $data['pageTitle'] = __('Forgot Password');
        return ResponseService::send([
            'data' => $data,
        ], view: viewss('auth','forgot'));
    }

    public function forgotPasswordProcess(Request $request)
    {
        dd($request->all());
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect()->route('login')
            ->with('success', __('You have been logged out successfully.'));
    }
}
