<?php

namespace Newnet\Slider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderItemRequest extends FormRequest
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
            'image' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'image' => __('slider::slider-item.image'),
        ];
    }
}
