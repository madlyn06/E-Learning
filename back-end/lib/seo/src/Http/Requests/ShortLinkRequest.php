<?php

namespace Newnet\Seo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortLinkRequest extends FormRequest
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
            'content_urls' => 'required',
            'text' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'code' => __('seo::message.code'),
            'content_urls' => __('seo::message.content_urls'),
            'text' => __('seo::message.text'),
        ];
    }
}
