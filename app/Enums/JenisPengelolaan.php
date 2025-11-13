<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum JenisPengelolaan: string
{
    use EnumTrait;

    case INSTITUSI = "INSTITUSI";
    case MASYARAKAT = "MASYARAKAT";

    public function label(): string
    {
        return match ($this) {
            self::INSTITUSI     => 'Institusi',
            self::MASYARAKAT    => 'Masyarakat',
        };
    }
}
