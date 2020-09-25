<?php declare(strict_types = 1);

namespace App\DTO;

final class ForgottenPasswordDTO
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