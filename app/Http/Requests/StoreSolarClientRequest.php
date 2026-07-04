<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolarClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => 'nullable|string|max:255',
            'contact'              => 'nullable|string|max:30',
            'whatsapp_number'      => 'nullable|string|max:30',
            'address'              => 'nullable|string|max:1000',
            'area'                 => 'nullable|string|max:255',
            'num_plates'           => 'nullable|integer|min:0',
            'package'              => 'nullable|string|max:255',
            'amount'               => 'nullable|numeric|min:0',
            'payment_mode'         => 'nullable|string|max:100',
            'received_by'          => 'nullable|string|max:255',
            'first_visit_date'     => 'nullable|date',
            'second_visit_date'    => 'nullable|date',
            'third_visit_date'     => 'nullable|date',
            'third_visit_notes'    => 'nullable|string|max:5000',
            'fourth_visit_date'    => 'nullable|date',
            'fourth_visit_notes'   => 'nullable|string|max:5000',
            'second_amount'        => 'nullable|numeric|min:0',
            'second_payment_mode'  => 'nullable|string|max:100',
            'second_received_by'   => 'nullable|string|max:255',
            'notes'                => 'nullable|string|max:5000',
        ];
    }
}
