<?php declare(strict_types = 1);

namespace App\DTO;

final class ForgottenPassword
{
    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Type("string"),
     *     @Assert\Email(),
     *     @AppAssert\UniqueEmail()
     * })
     */
    public $email;
}