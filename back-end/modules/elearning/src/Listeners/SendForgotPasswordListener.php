<?php

namespace Modules\Elearning\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Elearning\Events\UserForgotPassword;
use Modules\Elearning\Services\MailServiceInterface;

class SendForgotPasswordListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected $mailService;

    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }

    public function handle(UserForgotPassword $event)
    {
        $user = $event->user;
        $token = $event->token;
        $frontendResetUrl = config('app.frontend_url') . '/auth/reset-password?token=' . $token . '&email=' . urlencode($user->email);

        $this->mailService->sendResetPasswordEmail($user->email, $user->name, $frontendResetUrl);
    }
}
