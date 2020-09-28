<?php declare(strict_types = 1);

namespace App\Exception;

interface ApiExceptionInterface
{
    public function getMessage(): string;
    public function getCode(): int;
}