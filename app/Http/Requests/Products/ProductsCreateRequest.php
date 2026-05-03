<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductsCreateRequest extends FormRequest
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

            /*
            |--------------------------------------------------------------------------
            | BASIC
            |--------------------------------------------------------------------------
            */
            'name' => ['required', 'string', 'max:255'],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],

            'tagline' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | CATEGORY / RELATION
            |--------------------------------------------------------------------------
            */
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'brand_id' => ['nullable', 'integer'],

            /*
            |--------------------------------------------------------------------------
            | MEDIA
            |--------------------------------------------------------------------------
            */
            'image' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string'],

            'video_img' => ['nullable', 'string', 'max:255'],
            'video_link' => ['nullable', 'string', 'max:255'],

            /*
            |--------------------------------------------------------------------------
            | PRICING
            |--------------------------------------------------------------------------
            */
            'price' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'discount_type' => ['nullable', 'in:percent,flat'],

            'tax' => ['nullable', 'numeric'],
            'tax_type' => ['nullable', 'in:percent,flat'],

            /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */
            'stock' => ['nullable', 'integer'],
            'sold' => ['nullable', 'integer'],

            /*
            |--------------------------------------------------------------------------
            | CONTENT
            |--------------------------------------------------------------------------
            */
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'usage_instructions' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | ADVANCED (JSON)
            |--------------------------------------------------------------------------
            */
            'attributes' => ['nullable', 'array'],
            'features' => ['nullable', 'array'],
            'quantity_discounts' => ['nullable', 'array'],

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | FLAGS
            |--------------------------------------------------------------------------
            */
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'boolean'],
        ];
    }
    protected function prepareForValidation()
    {
        /*
        |----------------------------------------------------------------------
        | GALLERY JSON → ARRAY
        |----------------------------------------------------------------------
        */
        if ($this->gallery && is_string($this->gallery)) {
            $decoded = json_decode($this->gallery, true);

            $this->merge([
                'gallery' => is_array($decoded) ? $decoded : [],
            ]);
        }

        /*
        |----------------------------------------------------------------------
        | BOOLEAN FIX (checkbox / select issue)
        |----------------------------------------------------------------------
        */
        $this->merge([
            'status' => (int) $this->status,
            'is_featured' => (int) ($this->is_featured ?? 0),
        ]);
    }
}
