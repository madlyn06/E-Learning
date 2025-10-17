<?php

namespace Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
            'type' => 'required',
            'value' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('ecommerce::message.name'),
            'type' => __('ecommerce::message.type'),
            'value' => __('ecommerce::message.value'),
        ];
    }
}
