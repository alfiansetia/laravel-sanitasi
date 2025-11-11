<?php

namespace App\Enums;

enum JenisPengelolaan: string
{
    case INSTITUSI = "institusi";
    case MASYARAKAT = "masyarakat";

    public function label(): string
    {
        return match ($this) {
            self::INSTITUSI     => 'Institusi',
            self::MASYARAKAT    => 'Masyarakat',
        };
    }
}
