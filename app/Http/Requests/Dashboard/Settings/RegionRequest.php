<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;

class RegionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50'],
        ];
    }
}
