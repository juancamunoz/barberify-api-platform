<?php

declare(strict_types=1);

namespace App\Api\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonExceptionResponseTransformerListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $data = [
                'class' => \get_class($exception),
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ];

            $event->setResponse($this->prepareResponse($data, $data['code']));
        }

        if($exception instanceof \DomainException){
            $data = [
                'class' => \get_class($exception),
                'message' => $exception->getMessage(),
            ];

            $event->setResponse($this->prepareResponse($data, JsonResponse::HTTP_BAD_REQUEST));
        }
    }

    private function prepareResponse(array $data, int $statusCode): JsonResponse
    {
        $response = new JsonResponse($data, $statusCode);
        $response->headers->set('Server-Time', \time());
        $response->headers->set('X-Error-Code', $statusCode);

        return $response;
    }
}
