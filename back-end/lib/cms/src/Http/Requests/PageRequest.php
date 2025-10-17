<?php

namespace Newnet\Cms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        // $id = $this->route('id');

        return [
            'name' => 'required',
            // 'slug' => 'required|unique:cms__pages,slug,'.$id,
        ];
    }


    public function attributes()
    {
        return [
            'name' => __('cms::page.name'),
            'slug' => __('cms::page.slug'),
        ];
    }
}
