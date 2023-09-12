<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'bank_name' => 'required',
    'bank_account_number' => 'required',
    'bank_ifsc' => 'required',
    'branch_location' => 'required',
    'amount' => 'required',
    'payment_mode' => 'required',
    'mode' => 'required',
    'sender_receiver_name' => 'required'
];
    }
}