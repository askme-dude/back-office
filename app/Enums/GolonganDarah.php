<?php

namespace App\Enums;

enum GolonganDarah: string
{
    case O = 'O';
    case A = 'A';
    case B = 'B';
    case AB = 'AB';

    public static function count(): int
    {
        return count(self::cases());
    }
}
