<?php

namespace App\Enums;

enum Role: string
{
    case USER = "user";
    case ADMIN = "admin";

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER  => 'User',
        };
    }
}
