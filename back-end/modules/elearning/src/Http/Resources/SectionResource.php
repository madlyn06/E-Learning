<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'name' => $this->name,
            'description' => $this->description,
            'content' => $this->content,
            'display_order' => $this->display_order,
            'lessons' => LessonResource::collection($this->lessons),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
