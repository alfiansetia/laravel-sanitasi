<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum SumberDana: string
{
    use EnumTrait;

    case DAK = "DAK";
    case DAU = "DAU";

    public function label(): string
    {
        return match ($this) {
            self::DAK   => 'DAK',
            self::DAU   => 'DAU',
        };
    }
}
