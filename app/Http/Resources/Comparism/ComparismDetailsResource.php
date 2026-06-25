<?php

namespace App\Http\Resources\Comparism;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComparismDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'site_type' => $this->site_type,
            'area'      => $this->area,
            'status'    => $this->status,

            'areas' => $this->areas->map(function ($area) {
                return [
                    'id'         => $area->id,
                    'left_side'  => $area->left_side,
                    'right_side' => $area->right_side,
                    'sort_order' => $area->sort_order,
                    'status'     => $area->status,
                ];
            }),

            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}