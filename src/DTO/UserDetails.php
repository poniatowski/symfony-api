<?php declare(strict_types = 1);

namespace App\DTO;

use App\Util\CommandInterface as Command;
use App\Util\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserDetails implements PayloadInterface, Command
{
    public $id;

    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *          min = 3,
     *          max = 255
     *      )
     * }, groups={"edit"})
     */
    public $firstname;

    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *          min = 3,
     *          max = 255
     *      )
     * }, groups={"edit"})
     */
    public $surname;
}