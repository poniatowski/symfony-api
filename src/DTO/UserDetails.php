<?php declare(strict_types = 1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDetails
{
    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *          min = 3,
     *          max = 255
     *      )
     * })
     */
    public $firstname;

    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *          min = 3,
     *          max = 255
     *      )
     * })
     */
    public $surname;
}