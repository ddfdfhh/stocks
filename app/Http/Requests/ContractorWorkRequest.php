<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractorWorkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'driver_id' => 'required|numeric',
            'destination_address' => 'required|string',
            'total_cost' => 'required|numeric',
            'transport_cost' => 'required|numeric',
            'payment_received' => 'required|numeric',
            'payment_due' => 'numeric|nullable',
            'due_date' => 'nullable',
            'loaded_products' => 'nullable',
            'unloaded_products' => 'nullable',
            'payment_mode' => 'required',
            'customer_id' => 'required',
            'loaded_products__json__product_id[]\'' => 'nullable',
            'loaded_products__json__quantity[]\'' => 'nullable',
        ];
    }
}
