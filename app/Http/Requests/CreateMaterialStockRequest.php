<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMaterialStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|numeric',
            'driver_id' => 'numeric|nullable',
            'driver_name' => 'nullable',
            'eway_bill' => 'image|nullable',
            'vehicle_number' => 'nullable',
            'material_id' => 'required|numeric',
            'location' => 'required',
            'amount' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];
    }
}
