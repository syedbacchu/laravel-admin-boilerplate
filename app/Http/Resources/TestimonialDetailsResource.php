<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'review_text' => $this->review_text,
            'sort_order' => $this->sort_order,
            'review_star' => $this->review_star,
            'designation' => $this->designation,
            'image' => $this->image,
            'status' => $this->status,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}