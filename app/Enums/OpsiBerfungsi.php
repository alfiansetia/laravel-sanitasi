<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OpsiBerfungsi: string
{
    use EnumTrait;

    case BERFUNGSI = "BERFUNGSI";
    case TIDAK_BERFUNGSI = "TIDAK_BERFUNGSI";

    public function label(): string
    {
        return match ($this) {
            self::BERFUNGSI          => 'Berfungsi',
            self::TIDAK_BERFUNGSI    => 'Tidak Berfungsi',
        };
    }
}
