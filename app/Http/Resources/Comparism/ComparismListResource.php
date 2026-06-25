<?php

namespace App\Http\Resources\Comparism;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComparismListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'site_type' => $this->site_type,
            'area'      => $this->area,
            'status'    => $this->status,
        ];
    }
}