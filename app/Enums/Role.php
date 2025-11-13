<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum Role: string
{
    use EnumTrait;

    case USER = "USER";
    case ADMIN = "ADMIN";

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER  => 'User',
        };
    }
}
