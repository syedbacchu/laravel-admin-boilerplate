<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatteryWaterLeadDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'district'     => $this->district,
            'thana'        => $this->thana,
            'address'      => $this->address,
            'bottle_size'  => $this->bottle_size,
            'quantity'     => (int) $this->quantity,
            'unit_price'   => (float) $this->unit_price,
            'total_price'  => (float) $this->total_price,
            'status'       => $this->status ?? 0,
            'note'         => $this->note,
            'admin_note'   => $this->admin_note,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}