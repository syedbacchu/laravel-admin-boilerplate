<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DropshippingLeadDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'district'      => $this->district,
            'thana'         => $this->thana,
            'address'       => $this->address,

            'product'       => optional($this->product)->name,
            'product_range' => $this->product_range,

            'status'        => $this->status ?? 0,
            'note'          => $this->note,

            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}