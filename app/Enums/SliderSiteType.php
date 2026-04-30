<?php

namespace App\Enums;

enum SliderSiteType: int
{
    case GENERAL = 1;
    case SOLAR = 2;
    case CAR = 3;
    case DROP_SHIPPING = 4;

    public static function getSliderSiteTypeArray(): array
    {
        return [
            self::GENERAL->value => 'GENERAL',
            self::SOLAR->value => 'SOLAR',
            self::CAR->value => 'CAR',
            self::DROP_SHIPPING->value => 'DROP SHIPPING',
        ];
    }
}
