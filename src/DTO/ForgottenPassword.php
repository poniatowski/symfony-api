<?php declare(strict_types = 1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ForgottenPassword
{
    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Type("string"),
     *     @Assert\Email()
     * })
     */
    public $email;
}