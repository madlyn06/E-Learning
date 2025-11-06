<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Payment;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class CreatePaymentRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'payment_method_id' => 'required|exists:elearning__payment_methods,id',
            'course_id' => 'required|exists:elearning__courses,id',
            'membership_id' => 'nullable|exists:elearning__memberships,id',
            'amount' => 'required|numeric|min:1000',
            'currency' => 'nullable|string|size:3',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method_id.required' => 'Please select a payment method',
            'payment_method_id.exists' => 'The selected payment method is invalid',
            'course_id.exists' => 'The selected course is invalid',
            'membership_id.exists' => 'The selected membership is invalid',
            'amount.required' => 'Payment amount is required',
            'amount.numeric' => 'Payment amount must be a number',
            'amount.min' => 'Payment amount must be at least 1,000 VND',
        ];
    }
}
