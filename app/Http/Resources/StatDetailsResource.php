<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'link' => $this->link,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'added_by' => $this->added_by,
            'updated_by' => $this->updated_by,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}