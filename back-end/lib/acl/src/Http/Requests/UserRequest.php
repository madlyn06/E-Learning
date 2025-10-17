<?php

namespace Newnet\Acl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user');

        return [
            'slug'     => 'required|unique:admins,slug,' . $id,
            'name'     => 'required',
            'email'    => 'required|email|unique:admins,email,' . $id,
            'password' => $id ? '' : 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'     => __('acl::user.name'),
            'email'    => __('acl::user.email'),
            'password' => __('acl::user.password'),
        ];
    }
}
