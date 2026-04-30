<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image,
            'cover_image' => $this->cover_image,
            'bio' => $this->bio,
            'designation' => $this->designation,

            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'linkedin_url' => $this->linkedin_url,
            'instagram_url' => $this->instagram_url,
            'github_url' => $this->github_url,
            'youtube_url' => $this->youtube_url,

            'join_date' => $this->join_date,

            'site_type' => $this->site_type,
            'is_featured' => $this->is_featured,
            'status' => $this->status,

            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,

            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}