<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'store_id' => 'numeric|nullable',
    'product_id' => 'required|numeric',
    'location' => 'required|string',
    'title' => 'required',
    'total_cost' => 'required|numeric',
    'quantity' => 'required|numeric'
];
    }
}