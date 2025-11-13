<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OpsiAda: string
{
    use EnumTrait;

    case ADA = "ADA";
    case TIDAK_ADA = "TIDAK_ADA";

    public function label(): string
    {
        return match ($this) {
            self::ADA          => 'Ada',
            self::TIDAK_ADA    => 'Tidak Ada',
        };
    }
}
