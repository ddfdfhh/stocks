<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemoTableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'has_attributes' => 'nullable',
    'category_id' => 'required',
    'features' => 'nullable',
    'features__json__longitude[]\'' => 'nullable',
    'features__json__latitude[]\'' => 'nullable'
];
    }
}