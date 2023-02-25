<?php

declare(strict_types=1);

namespace App\Tests\Unit\LoyalityUsers\Domain\Model;

use App\LoyalityUsers\Domain\Event\UserRegistered;
use App\LoyalityUsers\Domain\Exception\CannotRegisterUserException;
use App\LoyalityUsers\Domain\Model\User;
use App\LoyalityUsers\Domain\ValueObject\Email;
use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    public function testRegisterSuccess(): void
    {
        $user = User::registerNew(
            Uuid::uuid4(),
            'Andrew Andrew',
            new Email('test@test.com'),
            new PhoneNumber('+19876542343')
        );

        $events = $user->releaseEvents();
        $this->assertCount(1, $events, 'Only 1 event expected');
        $this->assertInstanceOf(UserRegistered::class, array_pop($events));
    }

    public function testCannotRegisterUserWithoutPhoneOrEmail(): void
    {
        $this->expectException(CannotRegisterUserException::class);

        User::registerNew(
            Uuid::uuid4(),
            'Andrew Andrew',
            null,
            null
        );
    }
}
