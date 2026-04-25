<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureCategoryListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'image' => $this->image,
            'sort_order' => (int) $this->sort_order,
            'is_featured' => (bool) $this->is_featured,
            'status' => (bool) $this->status,
            'features_count' => $this->when(isset($this->features_count), $this->features_count),
            'features' => $this->whenLoaded('features', function () {
                return $this->features->map(fn ($feature) => [
                    'id' => $feature->id,
                    'title' => $feature->title,
                    'slug' => $feature->slug,
                    'thumbnail' => $feature->thumbnail,
                ])->values();
            }),
        ];
    }
}
