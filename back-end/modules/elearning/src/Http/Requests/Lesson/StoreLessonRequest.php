<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Lesson;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class StoreLessonRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'required|in:video,quiz,file,document,youtube',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'lesson_purpose' => 'nullable|string',
            'is_free' => 'nullable|boolean',
            'is_enabled' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer',
        ];

        switch ($this->input('type')) {
            case 'youtube':
                $rules['video_id'] = 'required|string';
                break;

            case 'video':
                $rules['video'] = 'nullable|file|mimetypes:video/mp4,video/avi,video/mov';
                break;

            case 'quiz':
                // Handle JSON questions if passed as string
                if ($this->has('questions') && is_string($this->questions)) {
                    $decoded = json_decode($this->questions, true);
                    $this->merge(['questions' => $decoded]);
                }

                $rules['questions'] = 'required|array|min:1';
                $rules['questions.*.question'] = 'required|string';
                $rules['questions.*.choices'] = 'required|array|min:2';
                $rules['questions.*.choices.*'] = 'required|string';
                $rules['questions.*.correct'] = 'required|integer|min:0';
                $rules['questions.*.explanation'] = 'nullable|string';
                break;

            case 'document':
                $rules['document'] = 'nullable|array';
                $rules['document.*'] = 'nullable|file|mimes:pdf,doc,docx,txt,rtf';
                break;

            case 'file':
                $rules['file'] = 'nullable|array';
                $rules['file.*'] = 'file|mimes:pdf,doc,docx,txt,rtf,zip,rar|max:20480';
                break;

            case 'mixed':
                $rules['video'] = 'nullable|file|mimetypes:video/mp4,video/avi,video/mov';
                $rules['document'] = 'nullable|file|mimes:pdf,doc,docx,txt,rtf';
                $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,txt,rtf,zip,rar';
                break;

            default:
                // No additional validation for other types
                break;
        }

        // if (in_array($this->input('type'), ['document', 'file'])) {
        //     $rules['document'] = 'required|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        // }
        // dd($rules);

        return $rules;
    }
}
