<?php

namespace Newnet\Contact\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailContact extends Mailable
{
    use Queueable, SerializesModels;

    private $contact;

    /**
     * SendEmailContact constructor.
     * @param $contact
     */
    public function __construct($contact)
    {
        //
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $info = $this->contact;
        return $this->from($address = $info->email, $name = __('contact::setting.email_name'))
            ->subject(__('contact::setting.email_subject'))
            ->view('contact::web.contact.contact-mail')
            ->with('info', $info);
    }
}
