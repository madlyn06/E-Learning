<?php

namespace Modules\Manage\Services;

use Illuminate\Support\Facades\Mail;

class SendEmailService
{
    protected $mailTo;

    protected $data;

    /**
     * This function build mail
     */
    public function build(array $data)
    {
        if (isset($data['email']) && $data['email']) {
            $this->mailTo = $data['email'];
        }
        $this->data = $data;
    }

    /**
     * This function send mail
     */
    public function send()
    {
        if ($this->mailTo) {
            $mailData = $this->data;
            Mail::send($mailData['template'], ['data' => $this->data], function ($m) use ($mailData) {
                $m->from(env('MAIL_USERNAME'), $mailData['subject']);
                $m->to($mailData['email'], $mailData['name'])->subject($mailData['subject']);
            });
        }
    }
}
