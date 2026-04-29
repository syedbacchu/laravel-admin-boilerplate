<?php

namespace App\Http\Requests\Stat;

use Illuminate\Foundation\Http\FormRequest;

class StatCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
        ];
    }
}
