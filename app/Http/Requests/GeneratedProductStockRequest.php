<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneratedProductStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
   
    'product_id' => 'required|numeric',
    'quantity_produced' => 'required|numeric',
    'raw_materials__json__material_id' => 'nullable',
    'raw_materials__json__quantity' => 'nullable'
];
    }
}