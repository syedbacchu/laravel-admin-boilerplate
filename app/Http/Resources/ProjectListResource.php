<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProjectListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description ?: Str::limit(strip_tags((string) $this->description), 150),
            'location' => $this->location,
            'savings' => $this->savings,
            'thumbnail' => $this->thumbnail,
            'project_url' => $this->project_url,
            'start_date' => optional($this->start_date)->format('Y-m-d'),
            'end_date' => optional($this->end_date)->format('Y-m-d'),
            'project_status' => $this->project_status,
            'status' => (bool) $this->status,
            'is_featured' => (bool) $this->is_featured,
            'sort_order' => (int) $this->sort_order,
            'site_type' => $this->site_type,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                ];
            }),
        ];
    }
}
