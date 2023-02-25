<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\EventListener;

use App\LoyalityUsers\Domain\Event\UserRegistered;
use App\LoyalityUsers\Domain\Model\User;
use App\LoyalityUsers\Domain\Service\NotifyNewUser;
use App\LoyalityUsers\Repository\UserRepository;
use App\Tool\DomainEvents\DomainEventsListener;
use App\Tool\DomainEvents\Event;

class SendNewUserNotification implements DomainEventsListener
{
    private UserRepository $userRepository;

    private NotifyNewUser $notifyNewUser;

    public function __construct(UserRepository $userRepository, NotifyNewUser $notifyNewUser)
    {
        $this->userRepository = $userRepository;
        $this->notifyNewUser = $notifyNewUser;
    }

    /**
     * @throws \Exception
     */
    public function handleEvent(Event $event): void
    {
        if (!$event instanceof UserRegistered) {
            return;
        }

        /** @var User|null $user */
        $user = $this->userRepository->find($event->userId);

        if ($user === null) {
            throw new \Exception('User not found');
        }

        $user->sendRegistrationNotification($this->notifyNewUser);
    }
}
