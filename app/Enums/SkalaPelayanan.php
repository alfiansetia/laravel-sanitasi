<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum SkalaPelayanan: string
{
    use EnumTrait;

    case PERKOTAAN = "PERKOTAAN";
    case KAWASAN_TERTENTU = "KAWASAN_TERTENTU";
    case PERMUKIMAN = "PERMUKIMAN";

    public function label(): string
    {
        return match ($this) {
            self::PERKOTAAN         => 'Skala Perkotaan',
            self::KAWASAN_TERTENTU  => 'Skala Kawasan Tertentu',
            self::PERMUKIMAN        => 'Skala Permukiman',
        };
    }
}
