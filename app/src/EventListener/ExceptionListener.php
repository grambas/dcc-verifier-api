<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private string $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();


        $this->logger->error('ABC Exception acquired: ' . $exception->getMessage(), [
            'e' => $exception,
        ]);

        if ($exception instanceof ApiException) {
            $event->setResponse(
                new JsonResponse(['errors' => $exception->errors], $exception->getStatusCode())
            );

            return;
        }

        if ($exception instanceof  NotFoundHttpException) {
            return;
        }

        if ('prod' === $this->env) {
            $event->setResponse(
                new JsonResponse(['errors' => 'Internal server error'], 500)
            );
        } else {
            $event->setResponse(
                new JsonResponse([
                    'message' => $exception->getMessage(),
                    'exception' => $exception->__toString(),
                ], 500)
            );
        }
    }
}
