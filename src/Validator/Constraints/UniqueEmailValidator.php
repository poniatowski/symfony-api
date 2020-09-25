<?php declare(strict_types = 1);

namespace App\Validator\Constraints;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($email, Constraint $constraint): void
    {
        if ($email === null || $email === '') {
            return;
        }

        if ($this->repository->findByEmailAddress($email) !== null)
        {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $email)
                ->addViolation();
        }
    }
}