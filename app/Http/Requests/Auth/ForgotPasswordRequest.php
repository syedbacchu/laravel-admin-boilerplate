<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ForgotPasswordRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required'],
            'email' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => __('Please enter your email, username, or phone number.'),
            'password.required' => __('Password is required.'),
        ];
    }

}
