<?php declare(strict_types = 1);

namespace App\Utility;

class TokenUtility
{
    public static function generate(int $length = 20): string
    {
        return bin2hex(random_bytes($length));
    }
}