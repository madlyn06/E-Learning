<?php

namespace Modules\Elearning\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Elearning\Support\CommonHelper;

class PublicLessonResource extends JsonResource
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
        return $result;
    }
}
