<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\ValueObject;

class Email
{
    public readonly string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
