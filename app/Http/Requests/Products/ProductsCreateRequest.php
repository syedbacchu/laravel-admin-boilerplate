<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductsCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id') ?? $this->input('edit_id');

        return [
            /*
            |------------------------------------------------------------------
            | BASIC
            |------------------------------------------------------------------
            */
            'name'    => ['required', 'string', 'max:255'],
            'slug'    => [
                'nullable', 'string', 'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'tagline' => ['nullable', 'string', 'max:255'],

            /*
            |------------------------------------------------------------------
            | CATEGORY / RELATION
            |------------------------------------------------------------------
            */
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'brand_id'    => ['nullable', 'integer'],

            /*
            |------------------------------------------------------------------
            | MEDIA
            |------------------------------------------------------------------
            */
            'image'      => ['nullable', 'string'],
            'gallery'    => ['nullable', 'array'],
            'gallery.*'  => ['nullable', 'string'],
            'video_img'  => ['nullable', 'string', 'max:255'],
            'video_link' => ['nullable', 'string', 'max:255'],

            /*
            |------------------------------------------------------------------
            | PRICING
            |------------------------------------------------------------------
            */
            'price'         => ['nullable', 'numeric', 'min:0'],
            'discount'      => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:percent,flat'],

            /*
            |------------------------------------------------------------------
            | TAX
            |------------------------------------------------------------------
            */
            'tax'      => ['nullable', 'numeric', 'min:0'],
            'tax_type' => ['nullable', 'in:percent,flat'],

            /*
            |------------------------------------------------------------------
            | STOCK
            |------------------------------------------------------------------
            */
            'stock' => ['nullable', 'integer', 'min:0'],
            'sold'  => ['nullable', 'integer', 'min:0'],

            /*
            |------------------------------------------------------------------
            | CONTENT
            |------------------------------------------------------------------
            */
            'short_description'  => ['nullable', 'string'],
            'description'        => ['nullable', 'string'],
            'usage_instructions' => ['nullable', 'string'],

            /*
            |------------------------------------------------------------------
            | ADVANCED (JSON arrays)
            |------------------------------------------------------------------
            */
            'attributes'   => ['nullable', 'array'],
            'features'     => ['nullable', 'array'],

            // Quantity Discounts
            'quantity_discounts'               => ['nullable', 'array'],
            'quantity_discounts.*.min_qty'     => ['nullable', 'integer', 'min:1'],
            'quantity_discounts.*.discount'    => ['nullable', 'numeric', 'min:0'],
            'quantity_discounts.*.type'        => ['nullable', 'in:percent,flat'],

            // Variations
            'variations'                           => ['nullable', 'array'],
            'variations.*.name'                    => ['nullable', 'string'],
            'variations.*.sku'                     => ['nullable', 'string'],
            'variations.*.price'                   => ['nullable', 'numeric', 'min:0'],
            'variations.*.stock'                   => ['nullable', 'integer', 'min:0'],
            'variations.*.attribute_value_id'      => ['nullable', 'integer'],
            'variations.*.status'                  => ['nullable', 'boolean'],

            /*
            |------------------------------------------------------------------
            | SEO
            |------------------------------------------------------------------
            */
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords'    => ['nullable', 'string'],

            /*
            |------------------------------------------------------------------
            | FLAGS
            |------------------------------------------------------------------
            */
            'is_featured' => ['nullable', 'boolean'],
            'status'      => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Gallery JSON string → array
        if ($this->gallery && is_string($this->gallery)) {
            $decoded = json_decode($this->gallery, true);
            $this->merge([
                'gallery' => is_array($decoded) ? $decoded : [],
            ]);
        }

        // Boolean fix
        $this->merge([
            'status'      => (int) $this->status,
            'is_featured' => (int) ($this->is_featured ?? 0),
        ]);

        // Variation status fix
        if ($this->variations) {
            $variations = collect($this->variations)->map(function ($var) {
                $var['status'] = isset($var['status']) ? (int) $var['status'] : 1;
                return $var;
            })->toArray();

            $this->merge(['variations' => $variations]);
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Product name is required.'),
            'status.required' => __('Status is required.'),
        ];
    }
}