<?php

namespace Modules\Elearning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Elearning\Models\User;

class UserForgotPassword
{
    use Dispatchable, SerializesModels;

    public $user;
    public $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
