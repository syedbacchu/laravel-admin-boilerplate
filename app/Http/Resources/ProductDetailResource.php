<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'image' => $this->image,
            'gallery' => $this->gallery ?? [],
            'video_img' => $this->video_img,
            'video_link' => $this->video_link,

            // Pricing
            'price' => (float) ($this->price ?? 0),
            'final_price' => (float) ($this->final_price ?? 0),
            'discount' => (float) ($this->discount ?? 0),
            'discount_type' => $this->discount_type,
            'tax' => (float) ($this->tax ?? 0),
            'tax_type' => $this->tax_type,

            // Stock
            'stock' => (int) ($this->stock ?? 0),
            'sold' => (int) ($this->sold ?? 0),

            // Content
            'short_description' => $this->short_description,
            'description' => $this->description,
            'usage_instructions' => $this->usage_instructions,

            // Attributes & Features
            'attributes' => $this->attributes ?? [],
            'features' => $this->features ?? [],
            'quantity_discounts' => $this->quantity_discounts ?? [],

            // Category
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                    'image' => $this->category?->image,
                ];
            }),

            // Multiple Categories
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'image' => $cat->image,
                    ];
                });
            }),

            // Variations
            'variations' => $this->whenLoaded('variations', function () {
                return $this->variations->map(function ($variation) {
                    return [
                        'id' => $variation->id,
                        'name' => $variation->name,
                        'sku' => $variation->sku,
                        'price' => (float) ($variation->price ?? 0),
                        'stock' => (int) ($variation->stock ?? 0),
                        'attributes' => $variation->attributes ?? [],
                        'status' => (bool) $variation->status,
                    ];
                });
            }),

            // Flags
            'is_featured' => (bool) ($this->is_featured ?? false),
            'status' => (bool) ($this->status ?? false),

            // SEO
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,

            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
