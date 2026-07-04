<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSofaCleaningRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_name'   => 'nullable|string|max:255',
            'contact'         => 'nullable|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'address'         => 'nullable|string|max:1000',
            'sofa_type'       => 'nullable|string|max:100',
            'num_seats'       => 'nullable|integer|min:0',
            'service_charges' => 'nullable|numeric|min:0',
            'booking_date'    => 'nullable|date',
            'service_date'    => 'nullable|date',
            'team'            => 'nullable|string|max:255',
            'status'          => 'nullable|string|max:100',
            'amount_received' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string|max:5000',
        ];
    }
}
