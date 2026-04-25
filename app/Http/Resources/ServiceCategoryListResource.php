<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCategoryListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'sort_order' => (int) $this->sort_order,
            'is_featured' => (bool) $this->is_featured,
            'status' => (bool) $this->status,
            'services_count' => $this->when(isset($this->services_count), $this->services_count),
            'services' => $this->whenLoaded('services', function () {
                return $this->services->map(fn ($service) => [
                    'id' => $service->id,
                    'title' => $service->title,
                    'slug' => $service->slug,
                    'thumbnail' => $service->thumbnail,
                ])->values();
            }),
        ];
    }
}
