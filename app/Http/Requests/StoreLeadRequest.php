<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'              => 'nullable|date',
            'name'              => 'nullable|string|max:255',
            'contact'           => 'nullable|string|max:30',
            'whatsapp_number'   => 'nullable|string|max:30',
            'platform'          => 'nullable|string|max:100',
            'campaign_name'     => 'nullable|string|max:255',
            'response'          => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'type_of_site'      => 'nullable|string|max:255',
            'type_of_service'   => 'nullable|string|max:255',
            'size_of_area'      => 'nullable|string|max:100',
            'num_items'         => 'nullable|integer|min:0',
            'booking_date'      => 'nullable|date',
            'service_date'      => 'nullable|date',
            'appointment_time'  => 'nullable|date_format:H:i',
            'estimated_amount'  => 'nullable|numeric|min:0',
            'amount_received'   => 'nullable|numeric|min:0',
        ];
    }
}
