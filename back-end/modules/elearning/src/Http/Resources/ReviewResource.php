<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'rating' => $this->rating,
            'review' => $this->review,
            'is_approved' => $this->is_approved,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->whenLoaded('user', function () {
                    return $this->user->id;
                }),
                'name' => $this->whenLoaded('user', function () {
                    return $this->user->name;
                }),
                'avatar' => $this->whenLoaded('user', function () {
                    return $this->user->profile_image;
                }),
            ],
        ];
    }
}
