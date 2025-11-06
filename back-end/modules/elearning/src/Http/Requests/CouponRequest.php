<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'code' => 'required|unique:elearning_coupons,code,' . $this->route('coupon'),
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:elearning_courses,id',
            'status' => 'nullable|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'code' => __('elearning::coupon.code'),
            'discount_type' => __('elearning::coupon.discount_type'),
            'discount_value' => __('elearning::coupon.discount_value'),
            'valid_from' => __('elearning::coupon.valid_from'),
            'valid_to' => __('elearning::coupon.valid_to'),
            'usage_limit' => __('elearning::coupon.usage_limit'),
            'course_ids' => __('elearning::coupon.courses'),
            'status' => __('elearning::coupon.status'),
        ];
    }
}
