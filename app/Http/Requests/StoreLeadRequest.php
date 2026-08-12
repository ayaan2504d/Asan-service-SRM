<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $leadId = $this->route('lead') ? $this->route('lead')->id : null;

        return [
            'name'              => 'required|string|max:255',
            'customer_name'     => 'nullable|string|max:255',
            'contact'           => [
                'required',
                'string',
                'max:30',
                Rule::unique('leads', 'contact')->ignore($leadId)->whereNull('deleted_at'),
            ],
            'whatsapp_number'   => 'nullable|string|max:30',
            'address'           => 'nullable|string',
            'location'          => 'nullable|string|max:255',
            'service_type'      => 'required|string|in:Solar Cleaning,Sofa Cleaning,Deep Cleaning',
            'type_of_service'   => 'nullable|string|max:255',
            'campaign_name'     => 'nullable|string|max:255',
            'lead_source'       => 'required|string|in:Facebook,Instagram,WhatsApp,Reference,Other',
            'platform'          => 'nullable|string|max:100',
            'status'            => 'required|string|in:Pending,Follow Up,Done,Cancel',
            'notes'             => 'nullable|string',
            
            // Dynamic Solar Cleaning fields
            'num_plates'        => 'nullable|integer|min:0',
            'quoted_amount'     => 'nullable|numeric|min:0',
            
            // Dynamic Sofa Cleaning fields
            'num_sofas'         => 'nullable|integer|min:0',
            'sofa_type'         => 'nullable|string|in:3 Seater,5 Seater,L Shape,Custom',
            
            // Dynamic Deep Cleaning fields
            'house_size'        => 'nullable|string|max:100',
            'floors'            => 'nullable|string|in:Ground,G+1,G+2,G+3',

            // Optional fields
            'date'              => 'nullable|date',
            'booking_date'      => 'nullable|date',
            'service_date'      => 'nullable|date',
            'appointment_time'  => 'nullable|date_format:H:i',
            'estimated_amount'  => 'nullable|numeric|min:0',
            'amount_received'   => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'contact.unique' => 'Customer Already Exists',
        ];
    }
}
