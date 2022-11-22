<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateZadElgadelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'lat' => 'required_if:address_type,==,map',
            'long' => 'required_if:address_type,==,map',
            'address' => 'required_if:address_type,==,link',
            'categories' => ['required', 'array'],
            'categories.*' => ['required', Rule::exists('category_of_zads', 'id')],
            'description' => ['required'],
            'whatsapp' => ['nullable'],
            'facebook_link' => ['nullable'],


        ];
    }
}
