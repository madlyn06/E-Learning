<?php

namespace Newnet\Slider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'slug' => 'required|unique:slider__sliders,slug,'.$this->route('id'),
        ];
    }

    public function attributes()
    {
        return [
            'name'  => __('slider::slider.name'),
            'slug'  => __('slider::slider.slug'),
        ];
    }
}
