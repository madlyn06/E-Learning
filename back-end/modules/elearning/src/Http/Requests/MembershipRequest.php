<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembershipRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'duration_type' => 'required|in:day,month,year',
            'features' => 'nullable|array',
            'status' => 'nullable|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('elearning::membership.name'),
            'description' => __('elearning::membership.description'),
            'price' => __('elearning::membership.price'),
            'duration' => __('elearning::membership.duration'),
            'duration_type' => __('elearning::membership.duration_type'),
            'features' => __('elearning::membership.features'),
            'status' => __('elearning::membership.status'),
        ];
    }
}
