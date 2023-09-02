<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'phone_no' => 'required',
    'address' => 'required',
    'image' => 'required|image',
    'adhar_number' => 'nullable',
    'adhar_image' => 'image|nullable',
    'dl_number' => 'nullable',
    'dl_image' => 'image|nullable',
    'status' => 'nullable',
    'vehicle_id' => 'numeric|nullable'
];
    }
}