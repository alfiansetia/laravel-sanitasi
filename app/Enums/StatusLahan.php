<?php

namespace App\Enums;

enum StatusLahan: string
{
    case HIBAH = "hibah";
    case ASET_PEMDA = "aset_pemda";
    case SWASTA = "swasta";
    case ASET_MASYARAKAT = "aset_masyarakat";

    public function label(): string
    {
        return match ($this) {
            self::HIBAH             => 'Hibah',
            self::ASET_PEMDA        => 'Aset Pemda',
            self::SWASTA            => 'Swasta',
            self::ASET_MASYARAKAT   => 'Aset Masyarakat (Pemilik Rumah)',
        };
    }
}
