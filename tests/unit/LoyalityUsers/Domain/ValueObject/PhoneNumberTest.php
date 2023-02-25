<?php

declare(strict_types=1);

namespace App\Tests\LoyalityUsers\Domain\ValueObject;

use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    private const US_NUMBER = '+11234567891';

    public function testCreatePhoneSuccess(): void
    {
        $phoneNumber = new PhoneNumber(self::US_NUMBER);

        $this->assertEquals(self::US_NUMBER, $phoneNumber->phoneNumber);
    }

    public function testNonUSPhone(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new PhoneNumber('+998123456789');
    }
}
