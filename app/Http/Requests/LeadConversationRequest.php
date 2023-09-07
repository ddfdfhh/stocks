<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadConversationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'conversation' => 'required|string',
            'lead_id' => 'required|numeric',
        ];
    }
}
