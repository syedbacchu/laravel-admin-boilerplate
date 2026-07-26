<?php

namespace App\Http\Resources\Package;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'short_description' => $this->short_description,
            'thumbnail'         => $this->thumbnail,

            'package_feature' => collect($this->package_feature ?? [])->map(function ($feature) {
                return [
                    'content'    => $feature['content'] ?? '',
                    'sort_order' => $feature['sort_order'] ?? 0,
                ];
            })->values(),

            'image'             => $this->image,
            'sort_order'        => $this->sort_order,
            'is_featured'       => $this->is_featured,
            'status'            => $this->status,
            'site_type'         => $this->site_type,
        ];
    }
}