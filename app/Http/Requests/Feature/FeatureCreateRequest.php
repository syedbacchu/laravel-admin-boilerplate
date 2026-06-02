<?php

namespace App\Http\Requests\Feature;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FeatureCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:features,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:500',
            'image' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:500',
            'category_id' => 'nullable|integer|exists:feature_categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
            'site_type' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'meta_image' => 'nullable|string|max:500',
        ];

        if ($this->edit_id) {
            $rules['slug'] = 'nullable|string|max:255|unique:features,slug,' . $this->edit_id;
        }

        return $rules;
    }
}
