<?php

namespace App\Http\Requests\CollectLead;

use App\Http\Requests\BaseFormRequest;

class CompanyDetailsRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Optional with defaults
            'site_type' => 'nullable|integer',

            // Required fields
            'company_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'grid_connection' => 'required|string|max:255',
            'working_shift' => 'required|string|max:255',
            'peak_load_time' => 'required|string|max:255',
            'roof_size' => 'required|string|max:255',
            'roof_type' => 'required|string|max:255',

            // Numeric fields
            'transformer_capacity' => 'nullable|numeric|min:0',
            'contract_demand' => 'nullable|numeric|min:0',
            'monthly_bill' => 'nullable|numeric|min:0',
            'monthly_consumption' => 'nullable|numeric|min:0',
            'total_connected_load' => 'nullable|numeric|min:0',
            'total_motor_load' => 'nullable|numeric|min:0',
            'daytime_load_percentage' => 'nullable|numeric|min:0|max:100',
            'demand_factor' => 'nullable|numeric|min:0|max:1',
            'diversity_factor' => 'nullable|numeric|min:0|max:1',
            'maximum_demand' => 'nullable|numeric|min:0',
            'daily_consumption' => 'nullable|numeric|min:0',
            'solar_target_percent' => 'nullable|numeric|min:0|max:100',
            'required_capacity_kw' => 'nullable|numeric|min:0',
            'system_size_kw' => 'nullable|numeric|min:0',
            'backup_hours' => 'nullable|integer|min:0',
            'critical_load' => 'nullable|numeric|min:0',
            'inverter_size' => 'nullable|numeric|min:0',
            'panel_size' => 'nullable|numeric|min:0',
            'panel_quantity' => 'nullable|integer|min:0',
            'estimated_project_cost' => 'nullable|numeric|min:0',
            'expected_payback_period' => 'nullable|numeric|min:0',

            // JSON fields
            'machinery_load_details' => 'nullable|array',
            'machinery_load_details.*.machine_name' => 'required_with:machinery_load_details|string|max:255',
            'machinery_load_details.*.qty' => 'required_with:machinery_load_details|integer|min:1',
            'machinery_load_details.*.rated_power_kw' => 'required_with:machinery_load_details|numeric|min:0',
            'machinery_load_details.*.running_hours_per_day' => 'required_with:machinery_load_details|numeric|min:0|max:24',
            'machinery_load_details.*.total_kw' => 'required_with:machinery_load_details|numeric|min:0',

            'motor_load_details' => 'nullable|array',
            'motor_load_details.*.motor_type' => 'required_with:motor_load_details|string|max:255',
            'motor_load_details.*.qty' => 'required_with:motor_load_details|integer|min:1',
            'motor_load_details.*.hp' => 'required_with:motor_load_details|numeric|min:0',
            'motor_load_details.*.kw' => 'required_with:motor_load_details|numeric|min:0',
            'motor_load_details.*.starting_type' => 'required_with:motor_load_details|string|max:255',

            // Optional fields
            'google_map' => 'nullable|string',
            'has_shadow' => 'nullable|boolean',
            'customer_signature' => 'nullable|string',
            'declaration_date' => 'nullable|date',
        ];
    }
}
