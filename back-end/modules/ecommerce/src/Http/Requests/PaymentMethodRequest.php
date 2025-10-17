<?php

namespace Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required',
            'owner' => 'required',
            'number' => 'required',
            'branch' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('ecommerce::message.name'),
            'owner' => __('ecommerce::message.name'),
            'code' => __('ecommerce::message.code'),
            'number' => __('ecommerce::message.code'),
            'branch' => __('ecommerce::message.code'),
        ];
    }
}
