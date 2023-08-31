<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'company_name' => 'required',
    'email' => 'required|email',
    'mobile_number' => 'required',
    'website' => 'required',
    'gst_number' => 'required',
    'pan_number' => 'required',
    'address' => 'required'
];
    }
}