<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lesson_id' => $this->lesson_id,
            'question' => $this->question,
            'choices' => json_decode($this->choices, true),
            'correct' => $this->correct,
            'explanation' => $this->explanation,
            'display_order' => $this->display_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
