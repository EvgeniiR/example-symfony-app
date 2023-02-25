<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\ValueObject;

class PhoneNumber
{
    public const US_PHONE_REGEX = '/^\+1[0-9]{10}$/';

    public readonly string $phoneNumber;

    public function __construct(string $phoneNumber)
    {
        if (preg_match(self::US_PHONE_REGEX, $phoneNumber) !== 1) {
            throw new \InvalidArgumentException('Invalid phone number provided');
        }

        $this->phoneNumber = $phoneNumber;
    }
}
