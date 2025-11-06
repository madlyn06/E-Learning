<?php

namespace Modules\Elearning\Services;

use Modules\Elearning\Models\MemberShip;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MembershipService
{
    public function register(array $data)
    {
        $membership = MemberShip::create($data);
        $adminEmail = Config::get('mail.admin_email');
        if ($adminEmail) {
            Mail::send('emails.admin-membership-notification', [
                'membership' => $membership,
            ], function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('New Membership Registration');
            });
        }
        return $membership;
    }
}
