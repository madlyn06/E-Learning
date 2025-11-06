<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Elearning\Support\CommonHelper;

class LessonResource extends JsonResource
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
            'is_selling' => $this->is_selling,
            'is_published' => $this->is_published,
            'continue_id' => $this->continue_id,
            'previous_id' => $this->previous_id,
            'display_order' => $this->display_order,
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
        return $result;
    }
}
