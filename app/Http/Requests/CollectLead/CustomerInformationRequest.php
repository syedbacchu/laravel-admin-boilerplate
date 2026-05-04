<?php

namespace App\Http\Requests\CollectLead;

use App\Http\Requests\BaseFormRequest;
use App\Rules\PhoneNumberBD;

class CustomerInformationRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Optional with defaults
            'type' => 'nullable|integer|in:1',
            'site_type' => 'nullable|integer',

            // Required fields
            'full_name' => 'required|string|max:255',
            'phone' => ['required', new PhoneNumberBD()],
            'customer_type' => 'required|string|max:255',
            'address' => 'required|string',
            'district' => 'required|string|max:255',
            'installation_site_type' => 'required|string|max:255',
            'electricity_source' => 'required|string|max:255',
            'meter_type' => 'required|string|max:255',
            'daytime_usage' => 'required|string|max:255',
            'system_type' => 'required|string|max:255',
            'main_purpose' => 'required|string|max:255',
            'budget_range' => 'required|string|max:255',
            'payment_preference' => 'required|string|max:255',
            'roof_size' => 'required|string|max:255',
            'roof_type' => 'required|string|max:255',
            'installation_area' => 'required|string|max:255',
            'lead_source' => 'required|string|max:255',
            'decision_maker' => 'required|string|max:255',
            'decision_time' => 'required|string|max:255',

            // Optional fields
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'nid' => 'nullable|string|max:255',
            'google_map' => 'nullable|string',
            'monthly_bill' => 'required|numeric|min:0',
            'system_size_kw' => 'required|numeric|min:0',
            'has_shadow' => 'required|boolean',
            'customer_signature' => 'required|string',
            'declaration_date' => 'required|date',
        ];
    }
}
