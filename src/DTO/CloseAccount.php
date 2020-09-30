<?php declare(strict_types = 1);

namespace App\DTO;

use App\Util\CommandInterface as Command;

final class CloseAccount implements Command
{
    public $id;
}