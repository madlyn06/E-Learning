<?php

namespace Modules\Manage\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            // 'name' => 'required',
            // 'email' => 'required|email',
            // 'username' => 'required',
            // 'captcha' => 'required|captcha'
        ];
    }

    public function messages()
    {
        return [
            'validation.captcha' => 'Invalid captcha!'
        ];
    }
}
