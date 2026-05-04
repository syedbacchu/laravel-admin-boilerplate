<?php

namespace App\Enums;

enum LeadType: int
{
    case CUSTOMER = 1;
    case COMPANY = 2;

    public static function getLeadTypeArray(): array
    {
        return [
            self::CUSTOMER->value => 'CUSTOMER',
            self::COMPANY->value => 'COMPANY',
        ];
    }
}
