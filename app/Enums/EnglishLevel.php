<?php

namespace App\Enums;

enum EnglishLevel: Int
{
    case NO_ENGLISH = 0;
    case BASIC = 5;
    case INTERMEDIATE = 10;
    case ADVANCED = 15;
    case FLUENTLY = 20;

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }
}

