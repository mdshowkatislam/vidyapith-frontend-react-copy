<?php

namespace App\Helper;

enum ClassEnum:int
{
    case ষষ্ঠ = 6;
    case সপ্তম = 7;
    case অষ্টম = 8;
    case নবম = 9;

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function caseName($value): ?string
    {
        $constants = self::constants();

        foreach ($constants as $name => $val) {
            if ($val->value == $value) {
                return $name;
            }
        }
        return null;
    }

    private static function constants(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);
        return $reflectionClass->getConstants();
    }
}
