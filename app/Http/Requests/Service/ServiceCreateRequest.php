<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('service') ?? $this->route('id') ?? $this->input('edit_id');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($id)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:0,1'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'meta_image' => ['nullable', 'string', 'max:500'],

            'feature_list' => ['nullable', 'array'],
            'feature_list.*.title' => ['required', 'string', 'max:255'],
            'feature_list.*.description' => ['nullable', 'string'],
            'feature_list.*.image' => ['nullable', 'string'],
            'feature_list.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The service title is required.',
            'title.max' => 'The service title must not exceed 255 characters.',
            'slug.unique' => 'This slug is already in use.',
            'short_description.max' => 'The short description must not exceed 500 characters.',
            'category_id.exists' => 'The selected category does not exist.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either active or inactive.',
            'meta_title.max' => 'The meta title must not exceed 255 characters.',
        ];
    }
}
