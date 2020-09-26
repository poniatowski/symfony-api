<?php declare(strict_types = 1);

namespace App\DTO;

use App\Util\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ForgottenPassword implements PayloadInterface
{
    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Type("string"),
     *     @Assert\Email()
     * }, groups={"create"})
     */
    public $email;
}