<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'lead_name' => 'required',
    'lead_phone_no' => 'required|numeric',
    'email' => 'required',
    'address' => 'required',
    'designation' => 'nullable',
    'whatsapp_no' => 'numeric|nullable',
    'source_id' => 'required|numeric|nullable',
    'product_id' => 'required|numeric',
    'type' => 'nullable',
    'status' => 'nullable',
    'followup_date' => 'nullable'
];
    }
}