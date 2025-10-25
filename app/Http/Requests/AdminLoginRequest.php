<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:180'], // email / phone / username
            'password' => ['required', 'string', 'min:6'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => __('Please enter your email, username, or phone number.'),
            'password.required' => __('Password is required.'),
            'password.min' => __('Password must be at least 6 characters.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        // Trim login input (safe for username/phone/email)
        $this->merge([
            'login' => strtolower(trim($this->login)),
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        // key = user input (login) + IP
        return Str::transliterate(Str::lower($this->input('login')) . '|' . $this->ip());
    }
}
