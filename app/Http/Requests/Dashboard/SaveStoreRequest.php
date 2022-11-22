<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveStoreRequest extends FormRequest
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
            'categories' => ['required'],
            'categories.*' => ['required', Rule::exists('category_of_stores', 'id')],
            'con_type' => ['required'],
            //            'address_type' => 'required_if:con_type,==,local',
            'address' => 'required_if:con_type,==,online',
            'description' => ['required'],
        ];
    }
}
