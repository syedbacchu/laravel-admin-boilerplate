<?php

namespace App\Http\Requests\BatteryWaterLead;

use App\Http\Requests\BaseFormRequest;
use App\Rules\PhoneNumberBD;

class BatteryWaterLeadCompanyDetailsRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'phone'       => ['required', new PhoneNumberBD()],
            'district'    => 'nullable|string|max:255',
            'thana'       => 'nullable|string|max:255',
            'address'     => 'nullable|string',
            'bottle_size' => 'required|string|max:50',
            'quantity'    => 'required|integer|min:1',

            'email'       => 'nullable|email|max:255',
            'note'        => 'nullable|string',

            'unit_price'  => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'status'      => 'nullable|integer',
            'admin_note'  => 'nullable|string',
        ];
    }
}