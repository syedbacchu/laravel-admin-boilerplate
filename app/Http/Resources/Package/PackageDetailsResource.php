<?php

namespace App\Http\Resources\Package;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'short_description' => $this->short_description,
            'description'       => $this->description,

            'thumbnail'         => $this->thumbnail,
            'image'             => $this->image,

            'sort_order'        => $this->sort_order,
            'is_featured'       => $this->is_featured,
            'status'            => $this->status,
            'site_type'         => $this->site_type,

            'meta_title'        => $this->meta_title,
            'meta_keywords'     => $this->meta_keywords,
            'meta_description'  => $this->meta_description,
            'meta_image'        => $this->meta_image,

            'package_feature' => collect($this->package_feature)->map(function ($feature) {
                return [
                    'content'    => $feature['content'] ?? '',
                    'sort_order' => $feature['sort_order'] ?? 0,
                ];
            }),

            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}