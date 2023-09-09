<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'title' => 'required',
    'order_id' => 'numeric|nullable',
    'paid_amount' => 'required|numeric',
    'due_amount' => 'numeric|nullable',
    'paid_date' => 'required',
    'due_date' => 'nullable',
    'payment_mode' => 'required|string',
    'transaction_id' => 'nullable',
    'bank_name' => 'nullable',
    'account_holder_name' => 'nullable',
    'bank_account_no' => 'nullable',
    'bank_ifsc' => 'nullable'
];
    }
}