<?php

namespace App\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeValueCreateRequest extends FormRequest
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
            'type_id' => 'required|exists:attribute_types,id',
            'name' => 'nullable|string|max:255',
            'icon' => 'nullable|string',
            'status' => 'nullable|in:0,1',
            'value' => 'nullable|string',
        ];
    }
}
