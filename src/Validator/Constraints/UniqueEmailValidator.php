<?php
declare(strict_types = 1);

namespace App\Validator\Constraints;

use App\Entity\Interfaces\UserInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueEmailValidator extends ConstraintValidator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $t = $this->repository->findByEmailAddress($value->getEmail(), $value->getId());

        if (!$this->repository->findByEmailAddress($value->getEmail(), $value->getId())) {
            $this->context
                ->buildViolation(UniqueEmail::MESSAGE)
                ->setCode(UniqueEmail::IS_UNIQUE_EMAIL_ERROR)
                ->addViolation();
        }
    }
}