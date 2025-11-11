<?php

namespace App\Enums;

enum SumberDana: string
{
    case DAK = "dak";
    case DAU = "dau";

    public function label(): string
    {
        return match ($this) {
            self::DAK   => 'DAK',
            self::DAU   => 'DAU',
        };
    }
}
