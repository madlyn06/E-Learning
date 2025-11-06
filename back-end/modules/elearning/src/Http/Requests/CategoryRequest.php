<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests;

class CategoryRequest extends BaseCustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'slug' => 'nullable',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:elearning__categories,id',
            'status' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('elearning::category.name'),
            'slug' => __('elearning::category.slug'),
            'description' => __('elearning::category.description'),
            'parent_id' => __('elearning::category.parent'),
            'status' => __('elearning::category.status'),
        ];
    }
}
