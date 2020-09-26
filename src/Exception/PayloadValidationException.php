<?php declare(strict_types = 1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PayloadValidationException extends Exception
{
    private ConstraintViolationListInterface $violations;

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): self
    {
        $this->violations = $violations;

        return $this;
    }
}