<?php

namespace App\Enums;

enum SkalaPelayanan: string
{
    case PERKOTAAN = "perkotaan";
    case KAWASAN_TERTENTU = "kawasan_tertentu";
    case PERMUKIMAN = "permukiman";

    public function label(): string
    {
        return match ($this) {
            self::PERKOTAAN         => 'Skala Perkotaan',
            self::KAWASAN_TERTENTU  => 'Skala Kawasan Tertentu',
            self::PERMUKIMAN        => 'Skala Permukiman',
        };
    }
}
