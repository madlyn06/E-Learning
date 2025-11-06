<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Coupon;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class ValidateCouponRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255',
            'course_id' => 'required|exists:elearning__courses,id',
        ];
    }
}
