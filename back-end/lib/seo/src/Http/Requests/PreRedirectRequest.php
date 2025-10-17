<?php

namespace Newnet\Seo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreRedirectRequest extends FormRequest
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
            'from_path' => 'required|unique:seo__pre_redirects,from_path,'.$this->route('id'),
            'to_url'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'from_path' => __('seo::pre-redirect.from_path'),
            'to_url'    => __('seo::pre-redirect.to_url'),
        ];
    }
}
