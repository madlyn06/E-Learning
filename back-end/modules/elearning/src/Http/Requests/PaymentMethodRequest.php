<?php

namespace Modules\Elearning\Http\Requests;

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
        $rules = [
            'name' => 'required',
            'description' => 'nullable',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ];
        if (!$this->isMethod('PUT')) {
            $rules['code'] = 'required';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => __('elearning::payment_method.name'),
            'code' => __('elearning::payment_method.code'),
        ];
    }
}
