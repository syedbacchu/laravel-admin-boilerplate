<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'short_description' => $this->short_description,
            'image' => $this->image,
            'price' => (float) ($this->price ?? 0),
            'final_price' => (float) ($this->final_price ?? 0),
            'discount' => (float) ($this->discount ?? 0),
            'discount_type' => $this->discount_type,
            'stock' => (int) ($this->stock ?? 0),
            'sold' => (int) ($this->sold ?? 0),
            'is_featured' => (bool) ($this->is_featured ?? false),
            'status' => (bool) ($this->status ?? false),
            'features' => $this->when($this->features, function () {
                return $this->features ?? [];
            }),

            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                    ];
                });
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
