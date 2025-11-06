<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Review;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class StoreReviewRequest extends BaseCustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:elearning__courses,id',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'The course ID is required.',
            'course_id.exists' => 'The selected course does not exist.',
            'rating.required' => 'Please provide a rating.',
            'rating.numeric' => 'The rating must be a number.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating cannot be greater than 5.',
            'review.max' => 'The review cannot be longer than 1000 characters.',
        ];
    }
}
