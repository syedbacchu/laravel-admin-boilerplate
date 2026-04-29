<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'sort_order' => $this->sort_order,
            'link' => $this->link,
        ];
    }
}