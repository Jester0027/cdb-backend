<?php

namespace App\EventSubscriber;

use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $exception;
    private $event;

    public function onKernelException(ExceptionEvent $event)
    {
        $this->exception = $event->getException();
        $this->event = $event;
        switch ($this->exception) {
            case $this->exception instanceof HttpException:
                $this->httpExceptionHandler();
                break;
            case $this->exception instanceof JWTDecodeFailureException:
                $this->jwtExceptionHandler();
                break;
            case $this->exception instanceof Exception:
                $this->exceptionHandler();
                break;
        }
    }

    private function httpExceptionHandler()
    {
        $data = [
            "code" => $this->exception->getStatusCode(), 
            "message" => $this->exception->getMessage()
        ];
        $response = new JsonResponse($data, $this->exception->getStatusCode());
        $this->event->setResponse($response);
    }

    private function jwtExceptionHandler()
    {
        $data = [
            "reason" => $this->exception->getReason(),
            "message" => $this->exception->getMessage()
        ];
        $response = new JsonResponse($data, 401);
        $this->event->setResponse($response);
    }

    private function exceptionHandler()
    {
        $data = [
            "message" => $this->exception->getMessage()
        ];
        $response = new JsonResponse($data);
        $this->event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
