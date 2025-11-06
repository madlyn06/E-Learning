<?php

namespace Modules\Elearning\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Elearning\Support\CommonHelper;
use Modules\Elearning\Http\Resources\QuestionResource;

class StartSessionLessonResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'slug' => CommonHelper::slugify($this->url),
            'section_id' => $this->section_id,
            'user_id' => $this->user_id,
            'summary' => $this->summary,
            'content' => $this->content,
            'lesson_purpose' => $this->lesson_purpose,
            'is_free' => $this->is_free,
            'is_enabled' => $this->is_enabled,
            'display_order' => $this->display_order,
            'duration_minutes' => $this->duration_minutes,
        ];
        if ($this->type === 'video') {
            $result['video'] = $this->video;
        } elseif ($this->type === 'file') {
            $result['file'] = $this->document;
        } elseif ($this->type === 'youtube') {
            $result['video_id'] = $this->video_id;
        } elseif ($this->type === 'quiz') {
            $result['quiz'] = $this->quiz;
            $result['questions'] = QuestionResource::collection($this->questions);
        }
        if ($request->learning_session) {
            $result['continue_id'] = $this->continue_id;
            $result['previous_id'] = $this->previous_id;
        }
        return $result;
    }
}
