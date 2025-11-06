<?php

namespace Modules\Elearning\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\User;

class CourseAddedToWishlist extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The course that was added to the wishlist.
     *
     * @var \Modules\Elearning\Models\Course
     */
    protected $course;

    /**
     * The user who added the course to the wishlist.
     *
     * @var \Modules\Elearning\Models\User
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param \Modules\Elearning\Models\Course $course
     * @param \Modules\Elearning\Models\User $user
     * @return void
     */
    public function __construct(Course $course, User $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Course Added to Your Wishlist')
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('You have added the course "' . $this->course->name . '" to your wishlist.')
            ->line('This course is created by ' . $this->course->user->name . '.')
            ->action('View Course', route('elearning.web.course.detail', $this->course->slug ?? $this->course->id))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'course_name' => $this->course->name,
            'user_id' => $this->user->id,
            'message' => 'You have added the course "' . $this->course->name . '" to your wishlist.',
            'url' => route('elearning.web.course.detail', $this->course->slug ?? $this->course->id),
        ];
    }
}
