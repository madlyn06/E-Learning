<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Course;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class StoreCourseRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'is_selling' => 'nullable|boolean',
            'user_id' => 'required|exists:elearning__users,id',
            'summary' => 'required|string',
            'content' => 'required|string',
            'purposes' => 'required|array',
            'requirements' => 'required|array',
            'categories'   => 'required|array',
        ];
    }
}
