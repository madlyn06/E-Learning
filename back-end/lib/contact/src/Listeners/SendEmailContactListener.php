<?php

namespace Newnet\Contact\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use Newnet\Contact\Mail\SendEmailContact;
use Newnet\Setting\Models\Setting;

class SendEmailContactListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $rule1 = env('MAIL_USERNAME', null);
//        $rule2 = Setting::where('name', 'contact_email')->first();
        $rule2 = setting('contact_email');
        $toemail = $rule2 ? $rule2 : $rule1;
        if ($toemail) {
            $contact = $event->contact;
            $mailable = new SendEmailContact($contact);
            Mail::to($toemail)->send($mailable);
        }
    }
}
