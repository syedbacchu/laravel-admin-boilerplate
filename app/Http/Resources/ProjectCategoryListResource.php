<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCategoryListResource extends JsonResource
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
            'projects_count' => $this->when(isset($this->projects_count), $this->projects_count),
            'projects' => $this->whenLoaded('projects', function () {
                return $this->projects->map(fn ($project) => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'thumbnail' => $project->thumbnail,
                ])->values();
            }),
        ];
    }
}
