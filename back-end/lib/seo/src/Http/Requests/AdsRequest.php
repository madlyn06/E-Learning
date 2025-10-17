<?php

namespace Newnet\Seo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdsRequest extends FormRequest
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
            'code' => 'required',
            'content' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('seo::message.keyword'),
            'code' => __('seo::message.code'),
            'content' => __('seo::message.content'),
        ];
    }
}
