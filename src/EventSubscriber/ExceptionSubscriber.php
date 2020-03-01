<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $exception;
    private $event;

    public function onKernelException(ExceptionEvent $event)
    {
        $this->exception = $event->getException();
        $this->event = $event;
        switch ($this->exception) {
            case $this->exception instanceof NotFoundHttpException:
                $this->setExceptionResponse("resource not found");
                break;
            case $this->exception instanceof MethodNotAllowedHttpException:
                $this->setExceptionResponse("method not allowed");
                break;
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $exception
     * @param string $message
     * @return void
     */
    private function setExceptionResponse(string $message = "Exception caught")
    {
        $data = ["errorCode" => $this->exception->getStatusCode(), "message" => $message];
        $response = new JsonResponse($data, $this->exception->getStatusCode());
        $this->event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
