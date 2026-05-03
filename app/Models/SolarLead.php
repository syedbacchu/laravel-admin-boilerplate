<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarLead extends Model
{
    use HasFactory;

    protected $table = 'solar_leads';

    protected $fillable = [
        'type',

        // Customer
        'full_name',
        'phone',
        'whatsapp',
        'email',
        'nid_or_business_id',
        'customer_type',

        'address',
        'district',
        'google_map',
        'installation_site_type',
        'installation_site_other',

        'electricity_source',
        'monthly_bill',
        'meter_type',
        'daytime_usage',

        'system_type',
        'system_size_kw',
        'main_purpose',
        'purpose_other',

        'roof_size',
        'roof_type',
        'roof_type_other',
        'has_shadow',
        'installation_area',

        'decision_maker',
        'decision_time',

        // Industrial
        'company_name',
        'contact_person',
        'factory_location',

        'grid_connection',
        'transformer_capacity',
        'contract_demand',
        'monthly_consumption',

        'machinery_load',
        'motor_load',

        'working_shift',
        'peak_load_time',
        'daytime_load_percent',

        'demand_factor',
        'diversity_factor',
        'maximum_demand',
        'daily_consumption',

        'solar_target_percent',
        'required_capacity_kw',

        'roof_area',
        'industrial_roof_type',
        'industrial_shadow',

        'backup_hours',
        'critical_load',

        'inverter_size',
        'panel_size',
        'panel_quantity',

        'signature',
        'submitted_date',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS (IMPORTANT for JSON)
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'machinery_load' => 'array',
        'motor_load' => 'array',
        'has_shadow' => 'boolean',
        'industrial_shadow' => 'boolean',
    ];
}