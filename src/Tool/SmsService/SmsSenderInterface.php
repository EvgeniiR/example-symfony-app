<?php

declare(strict_types=1);

namespace App\Tool\SmsService;

interface SmsSenderInterface
{
    /**
     * @throws \Exception
     */
    public function send(string $phoneNumber, string $text): void;
}
