<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'code' => $this->code,
            'skill' => $this->skill,
            'introduce' => $this->introduce,
            'bio' => $this->bio,
            'address' => $this->address,
            'is_teacher' => $this->is_teacher,
            'created_at' => $this->created_at,
            'total_students' => $this->totalStudents,
            'total_courses' => $this->totalCourses,
            'average_rating' => $this->averageRating,
            'total_reviews' => $this->totalReviews,
            // Social links
            'social_links' => [
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram,
                'github' => $this->github,
                'linkedin' => $this->linkedin,
                'website' => $this->website,
            ],
        ];

        // Include teacher information if the user is a teacher
        if ($this->is_teacher) {
            $data['teacher'] = [
                'status' => $this->teacher_status,
                'approved_at' => $this->teacher_approved_at,
                'video_intro' => $this->video_intro,
                'star' => $this->star,
                'teaching_experience' => $this->teaching_experience,
                'education_background' => $this->education_background,
                'certificates' => $this->certificates,
                'teaching_categories' => $this->teaching_categories,
            ];
        }

        return $data;
    }
}
