<?php declare(strict_types = 1);

namespace App\CommandBus;

interface HandlerInterface
{
    public function handle(CommandInterface $command);
}