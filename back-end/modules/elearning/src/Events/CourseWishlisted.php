<?php

namespace Modules\Elearning\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\User;
use Modules\Elearning\Models\Wishlist;

class CourseWishlisted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The wishlist item that was created.
     *
     * @var \Modules\Elearning\Models\Wishlist
     */
    public $wishlist;

    /**
     * The course that was wishlisted.
     *
     * @var \Modules\Elearning\Models\Course
     */
    public $course;

    /**
     * The user who wishlisted the course.
     *
     * @var \Modules\Elearning\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \Modules\Elearning\Models\Wishlist $wishlist
     * @param \Modules\Elearning\Models\Course $course
     * @param \Modules\Elearning\Models\User $user
     * @return void
     */
    public function __construct(Wishlist $wishlist, Course $course, User $user)
    {
        $this->wishlist = $wishlist;
        $this->course = $course;
        $this->user = $user;
    }
}
