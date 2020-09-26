<?php declare(strict_types = 1);

namespace App\ArgumentResolver;

use App\Exception\PayloadDeserializationException;
use App\Exception\PayloadValidationException;
use App\Util\PayloadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class PayloadResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return in_array(PayloadInterface::class, class_implements($argument->getType()), true);
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        try {
            $payload = $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                'json',
                [ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
            );
        } catch (Throwable $exception) {
            throw new PayloadDeserializationException();
        }

        switch ($request->getMethod()) {
            case 'POST':
                $validationGroup = 'create';
                break;
            case 'PUT':
            case 'PATCH':
                $validationGroup = 'edit';
                break;
            default:
                $validationGroup = null;
        }

        if (count($errors = $this->validator->validate($payload, null, [$validationGroup]))) {
            throw (new PayloadValidationException())->setViolations($errors);
        }

        yield $payload;
    }
}