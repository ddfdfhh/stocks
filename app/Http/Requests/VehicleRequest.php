<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'vehicle_number' => 'nullable',
    'model' => 'nullable',
    'vehicle_type' => 'required',
    'document1_image' => 'image|nullable',
    'document2_image' => 'image|nullable'
];
    }
}