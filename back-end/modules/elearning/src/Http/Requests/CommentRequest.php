<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'lesson_id' => 'required|exists:elearning__lessons,id',
            'parent_id' => 'nullable|exists:elearning__comments,id',
            'content' => 'required|string',
            'is_spam' => 'boolean',
        ];
    }
}
