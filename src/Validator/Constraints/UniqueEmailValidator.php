<?php
declare(strict_types = 1);

namespace App\Validator\Constraints;

use App\Entity\Interfaces\UserInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
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
        $t = $this->repository->findByEmailAddress($email);

        if ($this->repository->findByEmailAddress($email) !== null) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $email)
                ->addViolation();
        }
    }
}