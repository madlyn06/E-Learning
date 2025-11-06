<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Anyone can apply to be a teacher
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teaching_experience' => 'required|string|min:10',
            'education_background' => 'required|string|min:10',
            'certificates' => 'nullable|string',
            'teaching_categories' => 'required|array|min:1',
            'teaching_categories.*' => 'required|string',
            'bio' => 'required|string|min:50',
            'video_intro' => 'nullable|string|url',
            'profile_image' => 'nullable|string',
        ];
    }
}
