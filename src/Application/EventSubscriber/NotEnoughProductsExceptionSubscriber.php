<?php

declare(strict_types=1);

namespace App\Application\EventSubscriber;

use App\Domain\Exception\NotEnoughProductsException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class NotEnoughProductsExceptionSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void {
        $exception = $event->getThrowable();

        if ($exception instanceof NotEnoughProductsException) {
            $response = new JsonResponse(
                data  : [
                            'error'   => 'Not enough products available',
                            'message' => 'The requested quantity exceeds the available stock.',
                        ],
                status: Response::HTTP_BAD_REQUEST,
            );

            $event->setResponse($response);
        }
    }

}
