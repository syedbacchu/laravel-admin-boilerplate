<?php

namespace App\Http\Requests\Comparism;

use Illuminate\Foundation\Http\FormRequest;

class ComparismAreaCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'compare_id' => ['required', 'integer', 'exists:comparisms,id'],
            'left_side'  => ['required', 'array'],
            'right_side' => ['required', 'array'],
            'sort_order' => ['nullable', 'array'],
            'status'     => ['nullable', 'boolean'],
        ];
    }
}