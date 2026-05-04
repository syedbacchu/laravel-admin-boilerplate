<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectLeadListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_label' => $this->type == 1 ? 'Customer' : 'Company',
            'site_type' => $this->site_type,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'district' => $this->district,
            'company_name' => $this->when($this->type == 2, $this->company_name),
            'customer_type' => $this->when($this->type == 1, $this->customer_type),
            'lead_source' => $this->lead_source,
            'decision_maker' => $this->decision_maker,
            'decision_time' => $this->decision_time,
            'status' => $this->status ?? 'new',
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
