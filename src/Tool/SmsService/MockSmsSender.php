<?php

declare(strict_types=1);

namespace App\Tool\SmsService;

class MockSmsSender implements SmsSenderInterface
{
    public function send(string $phoneNumber, string $text): void
    {
        return;
    }
}
