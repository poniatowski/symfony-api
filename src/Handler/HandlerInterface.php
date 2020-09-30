<?php declare(strict_types = 1);

namespace App\Handler;

use App\Util\CommandInterface as Command;

interface HandlerInterface
{
    public function handle(Command $command);
}