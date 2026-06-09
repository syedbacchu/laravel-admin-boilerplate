<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseFormRequest;

class ProjectCreateRequest extends BaseFormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('gallery') && is_string($this->gallery)) {
            $this->merge([
                'gallery' => json_decode($this->gallery, true) ?? []
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:500',
            'savings' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:500',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'thumbnail' => 'nullable|string|max:500',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|string|max:500',
            'category_id' => 'nullable|integer|exists:project_categories,id',
            'project_status' => 'nullable|in:ongoing,hold,completed',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'meta_image' => 'nullable|string|max:500',
        ];

        if ($this->edit_id) {
            $rules['slug'] = 'nullable|string|max:255|unique:projects,slug,' . $this->edit_id;
        }

        return $rules;
    }
}
