<?php
declare(strict_types = 1);

namespace App\DTO;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class User
{
    public $id;

    /**
     * @Assert\Email
     * @AppAssert\UniqueEmail()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $password;

    /**
     * @Assert\NotBlank()
     */
    public $passwordConfirmation;
}