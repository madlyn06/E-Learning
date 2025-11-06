<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
            'status' => 'nullable|in:pending,active,completed,expired,cancelled',
            'completed_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => __('elearning::enrollment.user'),
            'course_id' => __('elearning::enrollment.course'),
            'status' => __('elearning::enrollment.status'),
            'price' => __('elearning::enrollment.price'),
            'payment_method' => __('elearning::enrollment.payment_method'),
            'payment_status' => __('elearning::enrollment.payment_status'),
            'completed_at' => __('elearning::enrollment.completed_at'),
            'expired_at' => __('elearning::enrollment.expired_at'),
        ];
    }
}
