<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'tagline' => $this->tagline,
            'photo' => $this->photo,
            'mobile_banner' => $this->mobile_banner,
            'link' => $this->link,
            'serial' => $this->serial,
            'type' => $this->type,
            'cta_button' => $this->cta_button,
            'stat' => $this->stat,
        ];
    }
}