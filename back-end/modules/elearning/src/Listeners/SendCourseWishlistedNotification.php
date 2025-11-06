<?php

namespace Modules\Elearning\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Elearning\Events\CourseWishlisted;
use Modules\Elearning\Notifications\CourseAddedToWishlist;

class SendCourseWishlistedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \Modules\Elearning\Events\CourseWishlisted  $event
     * @return void
     */
    public function handle(CourseWishlisted $event)
    {
        // Check if the user has notifications enabled
        if ($event->user->settings && isset($event->user->settings['notifications']['wishlist']) && !$event->user->settings['notifications']['wishlist']) {
            return;
        }

        // Send notification to the user
        if (method_exists($event->user, 'notify')) {
            $event->user->notify(new CourseAddedToWishlist($event->course, $event->user));
        }
    }
}
