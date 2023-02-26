<?php

declare(strict_types=1);

namespace App\Tool\ExceptionHandling;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
            ],
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof DomainException || $exception instanceof RequestParsingError) {
            $event->setResponse(
                new JsonResponse([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                ], 400)
            );
        }
    }
}
