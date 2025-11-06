<?php

namespace Modules\Elearning\Services;

use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{
    public function sendVerificationEmail(string $email, string $name, string $verifyLink): void
    {
        Mail::send('elearning::emails.verify-email', [
            'name' => $name,
            'verifyLink' => $verifyLink,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('Welcome to our elearning platform');
        });
    }
    
    public function sendResetPasswordEmail(string $email, string $name, string $resetLink): void
    {
        \Illuminate\Support\Facades\Mail::send('elearning::emails.reset-password', [
            'name' => $name,
            'resetLink' => $resetLink,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('Reset your password');
        });
    }
    
    public function sendPaymentSuccessEmail(string $email, array $data): void
    {
        Mail::send('elearning::emails.payment-success', $data, function ($message) use ($email, $data) {
            $message->to($email)
                ->subject('Payment Successful - Access Your ' . ucfirst($data['item_type']));
        });
    }
}
