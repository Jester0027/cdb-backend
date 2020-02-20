<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {
            $data = ["errorCode" => $exception->getStatusCode(), "message" => "resource not found"];
            $response = new JsonResponse($data, 404);
            $event->setResponse($response);
        } else if ($exception instanceof MethodNotAllowedHttpException) {
            $data = ["errorCode" => $exception->getStatusCode(), "message" => "method not allowed"];
            $response = new JsonResponse($data, 404);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
