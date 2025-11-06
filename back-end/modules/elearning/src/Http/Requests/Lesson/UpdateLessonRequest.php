<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Lesson;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class UpdateLessonRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        dd($this->all());
        $rules = [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:video,quiz,file,document',
            'summary' => 'sometimes|string',
            'content' => 'sometimes|string',
            'lesson_purpose' => 'sometimes|string',
            'is_free' => 'sometimes|boolean',
            'is_enabled' => 'sometimes|boolean',
            'display_order' => 'sometimes|integer',
            'video' => 'sometimes|file|mimetypes:video/mp4,video/avi,video/mov',
        ];

        if ($this->input('type') === 'video') {
            $rules['video'] = 'sometimes|file|mimetypes:video/mp4,video/avi,video/mov';
            $rules['video_type'] = 'sometimes|string';
            $rules['video_url'] = 'sometimes|url';
        }

        if ($this->input('type') === 'quiz') {
            $rules['questions'] = 'sometimes|array|min:1';
            $rules['questions.*.question'] = 'required_with:questions|string';
            $rules['questions.*.choices'] = 'required_with:questions|array|min:2';
            $rules['questions.*.choices.*'] = 'required_with:questions|string';
            $rules['questions.*.correct'] = 'required_with:questions|integer|min:0';
            $rules['questions.*.explanation'] = 'nullable|string';
        }

        if (in_array($this->input('type'), ['document', 'file'])) {
            $rules['document'] = 'sometimes|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }

        return $rules;
    }
}
