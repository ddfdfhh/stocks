<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:supplier,email,' . $this->supplier,
            'mobile_no' => 'required|unique:supplier,mobile_no,' . $this->supplier,

            'address' => 'required',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'gst_number' => 'required|nullable',
            'pan_number' => 'required|nullable',
        ];
    }
}
