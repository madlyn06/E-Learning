<?php

namespace Newnet\Cms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
//        $id = $this->route('id');

        return [
            'name' => 'required',
//            'slug' => 'required|unique:cms__categories,slug,'.$id,
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('cms::category.name'),
            'slug' => trans('cms::category.slug'),
        ];
    }
}
