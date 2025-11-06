<?php

namespace Newnet\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $auth = request()->id ? request()->id : auth('sanctum')->user()->id;
        return [
            'name' => 'required',
            'phone'     => 'required|numeric|unique:customer__customers,phone,'.$auth,
            'email'     => 'required|unique:customer__customers,email,'.$auth,
            'password'  => 'nullable|min:6|required_with:password_confirmation|string|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'name'                  => __('customer::customer.name'),
            'phone'                 => __('customer::customer.phone'),
            'email'                 => __('customer::customer.email'),
            'password'              => __('customer::customer.password'),
            'password_confirmation' => __('customer::customer.password_confirmation'),
        ];
    }
}
