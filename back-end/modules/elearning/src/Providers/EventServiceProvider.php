<?php

namespace Modules\Elearning\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Elearning\Events\CourseWishlisted;
use Modules\Elearning\Events\PaymentCompleted;
use Modules\Elearning\Events\UserForgotPassword;
use Modules\Elearning\Events\UserLoggedIn;
use Modules\Elearning\Events\UserRegistered;
use Modules\Elearning\Listeners\LogUserLoginListener;
use Modules\Elearning\Listeners\SendCourseWishlistedNotification;
use Modules\Elearning\Listeners\SendForgotPasswordListener;
use Modules\Elearning\Listeners\SendPaymentSuccessEmailListener;
use Modules\Elearning\Listeners\SendVerificationEmailListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendVerificationEmailListener::class,
        ],
        UserLoggedIn::class => [
            LogUserLoginListener::class,
        ],
        UserForgotPassword::class => [
            SendForgotPasswordListener::class,
        ],
        PaymentCompleted::class => [
            SendPaymentSuccessEmailListener::class,
        ],
        CourseWishlisted::class => [
            SendCourseWishlistedNotification::class,
        ],
    ];
}
