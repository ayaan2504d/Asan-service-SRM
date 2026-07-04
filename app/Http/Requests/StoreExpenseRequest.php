<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'        => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'amount'      => 'required|numeric|min:0',
        ];
    }
}
