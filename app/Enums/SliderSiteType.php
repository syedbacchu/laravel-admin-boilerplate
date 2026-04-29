<?php

namespace App\Enums;

enum SliderSiteType: int
{
    case SOLAR = 1;
    case CAR = 2;
    case DROPSHIPPING = 3;

    public static function getSliderSiteTypeArray(): array
    {
        return [
            self::SOLAR->value => 'SOLAR',
            self::CAR->value => 'CAR',
            self::DROPSHIPPING->value => 'DROPSHIPPING',
        ];
    }
}
