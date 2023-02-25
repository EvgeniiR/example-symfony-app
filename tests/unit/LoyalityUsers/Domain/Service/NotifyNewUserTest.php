<?php

declare(strict_types=1);

namespace App\Tests\Unit\LoyalityUsers\Domain\Service;

use App\LoyalityUsers\Domain\Service\NotifyNewUser;
use App\LoyalityUsers\Domain\ValueObject\Email;
use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use App\Tool\EmailService\EmailSenderInterface;
use App\Tool\SmsService\SmsSenderInterface;
use PHPUnit\Framework\TestCase;

class NotifyNewUserTest extends TestCase
{
    private const US_NUMBER = '+11234567891';
    private const EMAIL = 'test@example.com';

    private EmailSenderInterface $emailSenderMock;
    private SmsSenderInterface $smsSenderMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailSenderMock = $this->createMock(EmailSenderInterface::class);
        $this->smsSenderMock = $this->createMock(SmsSenderInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function testEmailPriority(): void
    {
        $notifyNewUserFunc = new NotifyNewUser($this->emailSenderMock, $this->smsSenderMock);

        $fullName = 'Evgenii R';
        $email = new Email(self::EMAIL);
        $phoneNumber = new PhoneNumber(self::US_NUMBER);

        $this->emailSenderMock->expects($this->once())->method('send')->with($email->email);

        $notifyNewUserFunc($fullName, $email, $phoneNumber);
    }

    public function testNotifySms(): void
    {
        $notifyNewUserFunc = new NotifyNewUser($this->emailSenderMock, $this->smsSenderMock);

        $fullName = 'Evgenii R';
        $email = null;
        $phoneNumber = new PhoneNumber(self::US_NUMBER);

        $this->smsSenderMock->expects($this->once())->method('send')->with($phoneNumber->phoneNumber);

        $notifyNewUserFunc($fullName, $email, $phoneNumber);
    }
}
