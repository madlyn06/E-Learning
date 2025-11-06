<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Comment;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class StoreCommentRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'lesson_id' => 'required|exists:elearning__lessons,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:elearning__comments,id',
        ];
    }
}
