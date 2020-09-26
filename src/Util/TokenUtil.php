<?php declare(strict_types = 1);

namespace App\Util;

class TokenUtil
{
    public static function generate(int $length = 20): string
    {
        return bin2hex(random_bytes($length));
    }
}