<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ProductFeatureCreateRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->input('edit_id');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('product_features', 'slug')->ignore($id),
            ],
            'sub_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => (int) ($this->status ?? 1),
            'sort_order' => (int) ($this->sort_order ?? 0),
        ]);
    }
}
