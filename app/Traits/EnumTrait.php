<?php

namespace App\Traits;

trait EnumTrait
{
    /**
     * Cek apakah value yang diberikan valid untuk enum ini
     */
    public static function isValid(string $value): bool
    {
        $value = strtolower(trim($value));

        foreach (static::cases() as $case) {
            if (strtolower($case->value) === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse string menjadi instance enum (null jika tidak cocok)
     */
    public static function parse(string $value): ?self
    {
        $value = strtolower(trim($value));

        foreach (static::cases() as $case) {
            if (strtolower($case->value) === $value) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Parse string menjadi enum, lempar Exception jika tidak valid
     */
    public static function parseOrFail(string $value): self
    {
        $enum = static::parse($value);

        if (!$enum) {
            throw new \ValueError("Invalid enum value '{$value}' for " . static::class);
        }

        return $enum;
    }
}
