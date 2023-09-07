<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'item_id' => 'required',
    'paid_amount' => 'required',
    'paid_date' => 'required',
    'due_amount' => 'nullable',
    'due_date' => 'nullable',
    'paid_user_id' => 'numeric',
    'remark' => 'nullable'
];
    }
}