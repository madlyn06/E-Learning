<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:elearning_courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'status' => 'nullable|in:pending,approved,rejected',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => __('elearning::review.user'),
            'course_id' => __('elearning::review.course'),
            'rating' => __('elearning::review.rating'),
            'title' => __('elearning::review.title'),
            'content' => __('elearning::review.content'),
            'status' => __('elearning::review.status'),
        ];
    }
}
