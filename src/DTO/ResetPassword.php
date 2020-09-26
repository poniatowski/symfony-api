<?php declare(strict_types = 1);

namespace App\DTO;

use App\Util\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ResetPassword implements PayloadInterface
{
    /**
     * @Assert\Sequentially({
     *      @Assert\NotBlank(),
     *      @Assert\Length(
     *          min = 8,
     *          max = 20
     *      ),
     *      @Assert\Regex(
     *          pattern="/[0-9]/",
     *          match=true,
     *          message="Your password needs to contains at least one number"
     *      ),
     *      @Assert\Regex(
     *          pattern="/[a-zA-Z]/",
     *          match=true,
     *          message="Your password needs to contains at least one letter"
     *      ),
     *      @Assert\Regex(
     *          pattern = "/[A-Z]/",
     *          match=true,
     *          message="Your password needs to contain a uppercase"
     *      )
     * }, groups={"create"})
     */
    public $password;

    /**
     * @Assert\NotBlank(groups={"create"})
     */
    public $passwordConfirmation;

    /**
     * @Assert\IsTrue(message="Password does not match the password confirmation.", groups={"create"})
     */
    public function isPassword(): bool
    {
        return ($this->password === $this->passwordConfirmation);
    }
}