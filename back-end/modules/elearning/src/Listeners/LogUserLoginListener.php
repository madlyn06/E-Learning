<?php

namespace Modules\Elearning\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Elearning\Events\UserLoggedIn;

class LogUserLoginListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserLoggedIn $event)
    {
        $user = $event->user;
        logger("User logged in: {$user->id}");
        // TODO Tracking user login
    }
}
