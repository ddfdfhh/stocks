<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'items' => 'nullable',
    'customer_id' => 'required|numeric',
    'driver_id' => 'numeric|nullable',
    'items__json__product_id' => 'nullable',
    'items__json__quantity[]\'' => 'nullable'
];
    }
}