<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'review_text' => $this->review_text,
            'review_star' => $this->review_star,
            'sort_order' => $this->sort_order,
            'designation' => $this->designation,
            'image' => $this->image,
        ];
    }
}