<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        'name' => 'required',
        'email' => 'required|email|unique:customer,email,'.$this->customer,
        'mobile_no' => 'required|unique:customer,mobile_no,'.$this->customer,
        'address' => 'required',
        'state_id' => 'required|numeric',
        'city_id' => 'required|numeric',
        'gst_number' => 'nullable',
        'pan_number' => 'nullable',
        'status' => 'nullable'
];
    }
}