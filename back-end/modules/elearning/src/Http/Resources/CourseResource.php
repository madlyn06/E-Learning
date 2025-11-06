<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Elearning\Http\Resources\CategoryResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'summary' => $this->summary,
            'course_purpose' => $this->purposes,
            'requirements' => $this->requirements,
            'sale_price' => $this->sale_price,
            'price' => $this->price,
            'students_count' => $this->students_count,
            'total_reviews' => $this->totalReviews,
            'categories' => CategoryResource::collection($this->categories),
            'user' => new UserResource($this->user),
            'sections' => SectionResource::collection($this->sections),
            'created_at' => $this->created_at,
            'is_enable' => $this->is_enable,
        ];
    }
}
