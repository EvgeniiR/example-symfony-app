<?php

declare(strict_types=1);

namespace App\Tool\EmailService;

class MockEmailSender implements EmailSenderInterface
{
    public function send(string $emailAddress, string $content): void
    {
        return;
    }
}
