<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OpsiTeknologi: string
{
    use EnumTrait;

    case INDIVIDUAL = "INDIVIDUAL";
    case KOMUNAL = "KOMUNAL";

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL    => 'Tangki Septik Individual',
            self::KOMUNAL       => 'Tangki Septik Komunal',
        };
    }
}
