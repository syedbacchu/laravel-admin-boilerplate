<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderDetailsResource extends JsonResource
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
            'position' => $this->position,
            'page' => $this->page,
            'video_link' => $this->video_link,
            'site_type' => $this->site_type,
            'serial' => $this->serial,
            'cta_button' => $this->cta_button,
            'stat' => $this->stat,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}