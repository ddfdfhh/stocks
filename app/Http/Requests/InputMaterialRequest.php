<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InputMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'unit_id' => 'required|numeric'
];
    }
}