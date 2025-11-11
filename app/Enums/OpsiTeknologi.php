<?php

namespace App\Enums;

enum OpsiTeknologi: string
{
    case INDIVIDUAL = "individual";
    case KOMUNAL = "komunal";

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL    => 'Tangki Septik Individual',
            self::KOMUNAL       => 'Tangki Septik Komunal',
        };
    }
}
