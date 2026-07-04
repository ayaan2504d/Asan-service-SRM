<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolarSiteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'client_name'  => 'nullable|string|max:255',
            'contact'      => 'nullable|string|max:30',
            'address'      => 'nullable|string|max:1000',
            'area'         => 'nullable|string|max:255',
            'num_plates'   => 'nullable|integer|min:0',
            'charges'      => 'nullable|numeric|min:0',
            'visit_date'   => 'nullable|date',
            'visit_time'   => 'nullable|date_format:H:i',
            'team'         => 'nullable|string|max:255',
            'status'       => 'nullable|string|max:100',
            'payment_type' => 'nullable|string|max:100',
            'received_by'  => 'nullable|string|max:255',
        ];
    }
}
