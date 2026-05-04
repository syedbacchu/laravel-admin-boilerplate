<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectLeadDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'type' => $this->type,
            'type_label' => $this->type == 1 ? 'Customer' : 'Company',
            'site_type' => $this->site_type,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'address' => $this->address,
            'google_map' => $this->google_map,
            'roof_size' => $this->roof_size,
            'roof_type' => $this->roof_type,
            'has_shadow' => (bool) $this->has_shadow,
            'lead_source' => $this->lead_source,
            'decision_maker' => $this->decision_maker,
            'decision_time' => $this->decision_time,
            'customer_signature' => $this->customer_signature,
            'declaration_date' => $this->declaration_date,
            'status' => $this->status ?? 'new',
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];

        if ($this->type == 1) {
            $data = array_merge($data, [
                'nid' => $this->nid,
                'customer_type' => $this->customer_type,
                'district' => $this->district,
                'installation_site_type' => $this->installation_site_type,
                'electricity_source' => $this->electricity_source,
                'monthly_bill' => $this->monthly_bill,
                'meter_type' => $this->meter_type,
                'daytime_usage' => $this->daytime_usage,
                'system_type' => $this->system_type,
                'system_size_kw' => $this->system_size_kw,
                'main_purpose' => $this->main_purpose,
                'budget_range' => $this->budget_range,
                'payment_preference' => $this->payment_preference,
                'installation_area' => $this->installation_area,
            ]);
        }

        if ($this->type == 2) {
            $data = array_merge($data, [
                'company_name' => $this->company_name,
                'grid_connection' => $this->grid_connection,
                'transformer_capacity' => $this->transformer_capacity,
                'contract_demand' => $this->contract_demand,
                'monthly_consumption' => $this->monthly_consumption,
                'machinery_load_details' => $this->machinery_load_details,
                'total_connected_load' => $this->total_connected_load,
                'motor_load_details' => $this->motor_load_details,
                'total_motor_load' => $this->total_motor_load,
                'working_shift' => $this->working_shift,
                'peak_load_time' => $this->peak_load_time,
                'daytime_load_percentage' => $this->daytime_load_percentage,
                'demand_factor' => $this->demand_factor,
                'diversity_factor' => $this->diversity_factor,
                'maximum_demand' => $this->maximum_demand,
                'daily_consumption' => $this->daily_consumption,
                'solar_target_percent' => $this->solar_target_percent,
                'required_capacity_kw' => $this->required_capacity_kw,
                'backup_hours' => $this->backup_hours,
                'critical_load' => $this->critical_load,
                'inverter_size' => $this->inverter_size,
                'panel_size' => $this->panel_size,
                'panel_quantity' => $this->panel_quantity,
                'estimated_project_cost' => $this->estimated_project_cost,
                'expected_payback_period' => $this->expected_payback_period,
            ]);
        }

        return $data;
    }
}
