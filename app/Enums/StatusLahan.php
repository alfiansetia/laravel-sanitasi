<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum StatusLahan: string
{
    use EnumTrait;

    case HIBAH = "HIBAH";
    case PEMDA = "PEMDA";
    case SWASTA = "SWASTA";
    case MASYARAKAT = "MASYARAKAT";

    public function label(): string
    {
        return match ($this) {
            self::HIBAH         => 'Hibah',
            self::PEMDA         => 'Aset Pemda',
            self::SWASTA        => 'Swasta',
            self::MASYARAKAT    => 'Aset Masyarakat (Pemilik Rumah)',
        };
    }
}
