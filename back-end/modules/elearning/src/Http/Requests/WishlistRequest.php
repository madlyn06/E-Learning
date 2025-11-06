<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishlistRequest extends FormRequest
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
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => __('elearning::wishlist.user'),
            'course_id' => __('elearning::wishlist.course'),
        ];
    }
}
