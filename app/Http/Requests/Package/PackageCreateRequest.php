<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class PackageCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $editId = $this->route('id') ?? $this->edit_id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:packages,slug,' . $editId],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'link' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'site_type' => ['required'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'meta_image' => ['nullable', 'string'],
            'items.*.package_feature' => ['nullable', 'array'],
            'items.*.package_feature.*.content' => ['nullable', 'string'],
            'items.*.package_feature.*.sort_order' => ['nullable', 'integer'],
        ];
    }
}