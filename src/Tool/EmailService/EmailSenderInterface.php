<?php

declare(strict_types=1);

namespace App\Tool\EmailService;

interface EmailSenderInterface
{
    /**
     * @throws \Exception
     */
    public function send(string $emailAddress, string $content): void;
}
