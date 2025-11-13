<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum Pengelola: string
{
    use EnumTrait;

    case DINAS = "DINAS";
    case UPT = "UPT";

    public function label(): string
    {
        return match ($this) {
            self::DINAS => 'Dinas',
            self::UPT   => 'UPT',
        };
    }
}
