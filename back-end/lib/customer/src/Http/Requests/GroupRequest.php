<?php

namespace Newnet\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name' => 'required',
            'slug' => 'required' . ($id ? '|unique:customer__groups,slug,' . $id : ''),
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('customer::group.name'),
            'slug' => __('customer::group.slug'),
        ];
    }
}
