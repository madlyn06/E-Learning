<?php

namespace Modules\Elearning\Http\Requests\Section;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class StoreSectionRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ];
    }
}
