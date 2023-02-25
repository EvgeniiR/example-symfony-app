<?php

declare(strict_types=1);

namespace App\Tool\DomainEvents;

interface DomainEventsPublisher
{
    /**
     * @return Event[]
     */
    public function releaseEvents(): array;
}
