<?php

namespace App\Http\Requests\Comparism;

use Illuminate\Foundation\Http\FormRequest;

class ComparismCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_type' => ['nullable', 'integer'],
            'area'      => ['required', 'string', 'max:255'],
            'status'    => ['required', 'boolean'],
        ];
    }
}