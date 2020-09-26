<?php declare(strict_types = 1);

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService
{
    protected ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value): array
    {
        $violations = $this->validator->validate($value);
        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $errors;
        }

        return [];
    }
}