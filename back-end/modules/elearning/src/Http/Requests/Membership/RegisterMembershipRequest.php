<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Membership;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class RegisterMembershipRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'expire_month' => 'required|integer',
            'content' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
