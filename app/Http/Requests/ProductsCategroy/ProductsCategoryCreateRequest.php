<?php

namespace App\Http\Requests\ProductsCategroy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductsCategoryCreateRequest extends FormRequest
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
    $id = $this->route('id') ?? $this->input('edit_id');

    return [
        'name' => ['required', 'string', 'max:255'],

        'slug' => [
            'nullable',
            'string',
            'max:255',
            Rule::unique('product_categories', 'slug')->ignore($id),
        ],

        'parent_id' => [
            'nullable',
            'exists:product_categories,id'
        ],

        'image' => ['nullable', 'string'],
        'cover_image' => ['nullable', 'string'],

        'meta_title' => ['nullable', 'string', 'max:255'],
        'meta_description' => ['nullable', 'string'],

        'sort_order' => ['nullable', 'integer'],

        'status' => ['required', 'boolean'],
    ];
}
}
