<?php

namespace Modules\Elearning\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Elearning\Events\UserRegistered;
use Modules\Elearning\Services\MailServiceInterface;

class SendVerificationEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected $mailService;

    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }

    public function handle(UserRegistered $event)
    {
        $user = $event->user;
        $token = $event->token;
        $frontendVerifyUrl = config('app.frontend_url') . '/auth/verify-success?token=' . $token . '&email=' . urlencode($user->email);;

        $this->mailService->sendVerificationEmail($user->email, $user->name, $frontendVerifyUrl);
    }
}
