<?php
declare(strict_types = 1);

namespace App\DTO;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @AppAssert\UniqueEmail()
 */
class User
{

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage = "Your name must be at least {{ limit }} characters long",
     *     maxMessage = "Your name cannot be longer than {{ limit }} characters",
     *     allowEmptyString = false
     * )
     */
    public $name;

    /**
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $password;

    /**
     * @Assert\IsTrue(message="The password cannot match your name")
     */
    public function isPasswordSafe(): bool
    {
        return $this->name !== null && $this->password !== null && $this->name !== $this->password;
    }
}