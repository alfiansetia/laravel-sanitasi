<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OpsiBaik: string
{
    use EnumTrait;

    case BAIK = "BAIK";
    case TIDAK_BAIK = "TIDAK_BAIK";

    public function label(): string
    {
        return match ($this) {
            self::BAIK          => 'Baik',
            self::TIDAK_BAIK    => 'Tidak Baik',
        };
    }
}
