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
    'has_attributes__json__size[]\'' => 'nullable',
    'has_attributes' => 'nullable',
    'has_attributes__json__color[]\'' => 'nullable'
];
    }
}