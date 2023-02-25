<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\Event;

use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-immutable
 */
class UserRegistered implements \App\Tool\DomainEvents\Event
{
    public readonly UuidInterface $userId;

    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }
}
