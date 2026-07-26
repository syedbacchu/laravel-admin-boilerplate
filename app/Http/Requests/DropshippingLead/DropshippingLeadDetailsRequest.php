<?php

namespace App\Http\Requests\DropshippingLead;

use App\Http\Requests\BaseFormRequest;
use App\Rules\PhoneNumberBD;

class DropshippingLeadDetailsRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'phone'         => ['required', new PhoneNumberBD()],
            'email'         => 'nullable|email|max:255',

            'district'      => 'nullable|string|max:255',
            'thana'         => 'nullable|string|max:255',
            'address'       => 'nullable|string',
            'country'       => 'required|nullable|string',

            'product_id'    => 'nullable|integer|exists:products,id',
            'product_range' => 'nullable|string|max:255',

            'note'          => 'nullable|string',

            'status'        => 'nullable|integer',
        ];
    }
}