<?php
namespace Modules\Elearning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Elearning\Models\User;

class UserLoggedIn
{
    use Dispatchable, SerializesModels;

    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
