<?php

namespace Newnet\Menu\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
            'menu_id'            => 'required',
            'label'              => 'required',
            'menu_builder_class' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'menu_id'            => __('menu::menu-item.menu_id'),
            'label'              => __('menu::menu-item.label'),
            'menu_builder_class' => __('menu::menu-item.menu_builder_class'),
        ];
    }
}
