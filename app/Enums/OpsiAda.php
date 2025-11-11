<?php

namespace App\Enums;

enum OpsiAda: string
{
    case ADA = "ada";
    case TIDAK_ADA = "tidak_ada";

    public function label(): string
    {
        return match ($this) {
            self::ADA          => 'Ada',
            self::TIDAK_ADA    => 'Tidak Ada',
        };
    }
}
