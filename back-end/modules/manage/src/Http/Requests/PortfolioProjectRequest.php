<?php

namespace Modules\Manage\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioProjectRequest extends FormRequest
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
            'description' => 'required',
            'content' => 'required',
            'category_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('manage::message.name'),
            'description' => __('manage::message.description'),
            'content' => __('manage::message.content'),
            'category_id' => __('manage::message.category_id'),
        ];
    }
}
