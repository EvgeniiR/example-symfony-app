<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\Service;

use App\LoyalityUsers\Domain\ValueObject\Email;
use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use App\Tool\EmailService\EmailSenderInterface;
use App\Tool\SmsService\SmsSenderInterface;

class NotifyNewUser
{
    private EmailSenderInterface $emailSender;

    private SmsSenderInterface $smsSender;

    public function __construct(EmailSenderInterface $emailSender, SmsSenderInterface $smsSender)
    {
        $this->emailSender = $emailSender;
        $this->smsSender = $smsSender;
    }

    /**
     * Send notification to newly registered user. Email is preferred method.
     * @throws \Exception
     */
    public function __invoke(string $fullName, ?Email $email, ?PhoneNumber $phoneNumber): void
    {
        if ($email !== null) {
            $content = $this->renderEmailTemplate($fullName);
            $this->emailSender->send($email->email, $content);

            return;
        }

        if ($phoneNumber !== null) {
            $content = $this->renderSmsTemplate($fullName);
            $this->smsSender->send($phoneNumber->phoneNumber, $content);

            return;
        }

        throw new \Exception('Unable to send registration notification. User have no contacts specified');
    }

    private function renderEmailTemplate(string $fullName): string
    {
        // Twig or some other template engine may be used there to render emails. I decided not to do it there for simplicity
        return "Hello, {$fullName}, we inform you that your registration in loyality service was successful";
    }

    private function renderSmsTemplate(string $fullName): string
    {
        return "Hello, {$fullName}, we inform you that your registration in loyality service was successful";
    }
}
