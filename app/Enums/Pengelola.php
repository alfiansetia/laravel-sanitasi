<?php

namespace App\Enums;

enum Pengelola: string
{
    case DINAS = "dinas";
    case UPT = "upt";

    public function label(): string
    {
        return match ($this) {
            self::DINAS => 'Dinas',
            self::UPT   => 'UPT',
        };
    }
}
