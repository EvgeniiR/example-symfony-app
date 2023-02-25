<?php

declare(strict_types=1);

namespace App\Tool\DomainEvents;

interface DomainEventsListener
{
    /**
     * @throws \Exception
     */
    public function handleEvent(Event $event): void;
}
