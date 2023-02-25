<?php

declare(strict_types=1);

namespace App\Tool\DomainEvents;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class DomainEventsDispatcher implements EventSubscriberInterface
{
    /**
     * @var iterable<DomainEventsListener>
     */
    private iterable $domainEventsListeners;

    private readonly ManagerRegistry $doctrine;

    private LoggerInterface $logger;

    private bool $insidePostFlush = false;

    /**
     * @param iterable<DomainEventsListener> $domainEventsListeners
     */
    public function __construct(iterable $domainEventsListeners, ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->domainEventsListeners = $domainEventsListeners;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postFlush,
        ];
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if ($this->insidePostFlush) {
            return;
        }
        $this->insidePostFlush = true;

        $entitiesCount = 0;
        foreach ($args->getObjectManager()->getUnitOfWork()->getIdentityMap() as $identityMapEntry) {
            $entitiesCount += $this->processIdentityMapEntry($identityMapEntry);
        }

        $this->insidePostFlush = false;
        if ($entitiesCount > 0) {
            $this->doctrine->getManager()->flush();
        }
    }

    /**
     * @param array<string, object|null> $identityMapEntry
     * @return int Processed events count
     */
    private function processIdentityMapEntry(array $identityMapEntry): int
    {
        $processedEvents = 0;
        foreach ($identityMapEntry as $entity) {
            if ($entity instanceof DomainEventsPublisher) {
                $events = $entity->releaseEvents();
                $processedEvents += count($events);
                foreach ($events as $event) {
                    $this->dispatchEvent($event);
                }
            }
        }

        return $processedEvents;
    }

    private function dispatchEvent(Event $event): void
    {
        foreach ($this->domainEventsListeners as $listener) {
            try {
                $listener->handleEvent($event);
            } catch (\Exception $e) {
                // log exception, save event data for retry/debug

                $this->logger->error('Error happened during processing domain event: '.print_r($event, true));
            }
        }
    }
}
