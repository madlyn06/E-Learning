<?php

namespace Newnet\Acl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $userId = \Auth::guard('admin')->id();

        return [
            'name'     => 'required',
            'email'    => 'required|unique:admins,email,' . $userId,
            'password' => 'nullable|required_with:password_confirmation|string|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => __('acl::profile.name'),
            'email'                 => __('acl::profile.email'),
            'password'              => __('acl::profile.password'),
            'password_confirmation' => __('acl::profile.password_confirmation'),
        ];
    }
}
