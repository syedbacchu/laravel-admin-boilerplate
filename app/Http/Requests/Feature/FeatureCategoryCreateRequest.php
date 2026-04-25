<?php

namespace App\Http\Requests\Feature;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FeatureCategoryCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:feature_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
        ];

        if ($this->edit_id) {
            $rules['slug'] = 'nullable|string|max:255|unique:feature_categories,slug,' . $this->edit_id;
        }

        return $rules;
    }
}
