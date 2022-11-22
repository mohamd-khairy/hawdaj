<?php

namespace Modules\Report\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed $report_list
 */
class ReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'type' => 'sometimes|required|in:specific,comparison',
            'time_type' => 'sometimes|required|in:dynamic,specific',
            'report_list' => ['sometimes', 'required'],
            'site_id' => [
                'sometimes','required_if:type,specific',
                Rule::exists('sites', 'id')
            ],
            'site_ids' => [
                'sometimes','required_if:type,comparison',
                Rule::exists('sites', 'id')
            ]
        ];

        if (request('time_type') == 'specific') {
            $rules += [
                'start' => [
                    'required', 'date', 'date_format:Y-m-d', 'before:end'
                ],
                'end' => [
                    'required', 'date', 'date_format:Y-m-d', 'after:start'
                ]
            ];
        }


        return $rules;
    }

    public function messages(): array
    {
        return [
            'site_id.required_if' => trans('dashboard.site_required'),
            'site_ids.required_if' => trans('dashboard.site_required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
