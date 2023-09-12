<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferProductStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'title' => 'required',
    'store_id' => 'required|numeric',
    'product_id' => 'required|numeric',
    'quantity' => 'required|numeric'
];
    }
}