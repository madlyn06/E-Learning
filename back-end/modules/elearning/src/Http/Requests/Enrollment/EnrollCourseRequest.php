<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Enrollment;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class EnrollCourseRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:elearning__courses,id',
            'payment_method_id' => 'required|exists:elearning__payment_methods,id',
            'coupon_code' => 'nullable|string|max:255',
        ];
    }
}
