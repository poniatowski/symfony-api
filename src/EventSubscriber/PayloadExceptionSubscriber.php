<?php declare(strict_types = 1);

namespace App\EventSubscriber;

use App\Exception\PayloadDeserializationException;
use App\Exception\PayloadValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PayloadExceptionSubscriber implements EventSubscriberInterface
{
    private function normalizeViolations(ConstraintViolationListInterface $violations): ?array
    {
        $errors = null;

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof PayloadDeserializationException) {
            $event->setResponse(new JsonResponse(
                'Unable to parse the request body. Please ensure that it is a valid JSON format.',
                JsonResponse::HTTP_BAD_REQUEST
            ));

            return;
        }

        if ($exception instanceof PayloadValidationException) {
            $event->setResponse(new JsonResponse(
                $this->normalizeViolations($exception->getViolations()),
                JsonResponse::HTTP_BAD_REQUEST)
            );

            return;
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}