<?php

namespace Newnet\Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckMailConfig extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * SendEmailContact constructor.
     * @param $contact
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(__('core::setting-mail.email_subject'))
            ->view('core::admin.theme-setting.contact-mail');
    }
}
