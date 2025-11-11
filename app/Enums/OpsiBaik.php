<?php

namespace App\Enums;

enum OpsiBaik: string
{
    case BAIK = "baik";
    case TIDAK_BAIK = "tidak_baik";

    public function label(): string
    {
        return match ($this) {
            self::BAIK          => 'Baik',
            self::TIDAK_BAIK    => 'Tidak Baik',
        };
    }
}
