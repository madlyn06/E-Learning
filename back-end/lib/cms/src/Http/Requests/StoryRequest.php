<?php

namespace Newnet\Cms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoryRequest extends FormRequest
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
            'post_id' => 'required',
            'slug' => 'required|unique:cms__stories,slug,'.$this->route('id'),
        ];
    }

    public function attributes()
    {
        return [
            'name'  => __('cms::story.name'),
            'slug'  => __('cms::story.slug'),
            'post_id'  => __('cms::story.post_id'),
        ];
    }
}
