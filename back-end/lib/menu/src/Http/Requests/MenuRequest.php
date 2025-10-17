<?php

namespace Newnet\Menu\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'slug' => 'required|unique:menu__menus,slug,'.$this->route('id'),
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('menu::menu.name'),
            'slug' => __('menu::menu.slug'),
        ];
    }
}
