<?php

namespace Modules\Elearning\Services;

interface MailServiceInterface
{
    public function sendVerificationEmail(string $email, string $name, string $verifyLink): void;

    public function sendResetPasswordEmail(string $email, string $name, string $resetLink): void;
    
    public function sendPaymentSuccessEmail(string $email, array $data): void;
}
