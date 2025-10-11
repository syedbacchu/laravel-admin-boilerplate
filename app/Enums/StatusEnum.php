<?php
namespace App\Enums;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case PENDING = 0;
    case DELETED = 2;

    public static function getStatusArray(): array
    {
        return [
            self::ACTIVE->value => 'Active',
            self::PENDING->value => 'Pending',
            self::DELETED->value => 'Deleted',
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::PENDING => 'Pending',
            self::DELETED => 'Deleted',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::PENDING => 'yellow',
            self::DELETED => 'red',
        };
    }
}
