<?php

declare(strict_types=1);

namespace App\Tool\DomainEvents;

trait DomainEvents
{
    /**
     * @var Event[]
     */
    private array $pendingEvents;

    /**
     * @return Event[]
     */
    public function releaseEvents(): array
    {
        [$events, $this->pendingEvents] = [$this->pendingEvents, []];

        return $events;
    }

    protected function rememberThat(Event $event): void
    {
        $this->pendingEvents[] = $event;
    }
}
