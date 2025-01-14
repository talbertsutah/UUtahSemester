<?php

namespace talbertsutah\UUtahSemester\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
        
    }

    public static function namesDoubled(): array
    {
        return array_column(self::cases(), 'name', 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function valuesDoubled(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }

    public static function asArray(): array
    {
        if (empty(self::values())) {
            return self::names();
        }
        
        if (empty(self::names())) {
            return self::values();
        }
        
        return array_column(self::cases(), 'value', 'name');
    }

    public static function asArrayReversed(): array
    {
        if (empty(self::names())) {
            return self::values();
        }

        if (empty(self::values())) {
            return self::names();
        }
        
        return array_column(self::cases(), 'name', 'value');        
    }
}
