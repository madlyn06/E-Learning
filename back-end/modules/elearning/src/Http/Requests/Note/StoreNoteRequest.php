<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Note;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class StoreNoteRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'lesson_id' => 'required|exists:elearning__lessons,id',
            'content' => 'required|string',
            'time_iso' => 'required|string',
            'time_seconds' => 'required|numeric',
        ];
    }
}
