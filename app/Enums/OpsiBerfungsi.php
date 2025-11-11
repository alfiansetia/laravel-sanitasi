<?php

namespace App\Enums;

enum OpsiBerfungsi: string
{
    case BERFUNGSI = "berfungsi";
    case TIDAK_BERFUNGSI = "tidak_berfungsi";

    public function label(): string
    {
        return match ($this) {
            self::BERFUNGSI          => 'Berfungsi',
            self::TIDAK_BERFUNGSI    => 'Tidak Berfungsi',
        };
    }
}
