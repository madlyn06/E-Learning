<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $id = $this->route('user') ?? $this->route('teacher');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => $this->isMethod('PUT') || $this->isMethod('PATCH') 
                ? 'nullable|min:6' 
                : 'required|min:6',
            'role' => 'required|in:student,teacher,admin',
            'status' => 'nullable|in:active,inactive,pending,rejected',
            'bio' => 'nullable|string',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            if (!$this->has('password')) {
                unset($rules['password']);
            }
        }

        return $rules;
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('elearning::user.name'),
            'email' => __('elearning::user.email'),
            'password' => __('elearning::user.password'),
            'role' => __('elearning::user.role'),
            'status' => __('elearning::user.status'),
            'bio' => __('elearning::user.bio'),
        ];
    }
}
