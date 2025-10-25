<?php


namespace App\Enums;

enum UserRole: int
{
    case ALL = 0;
    case SUPER_ADMIN_ROLE = 1;
    case ADMIN_ROLE = 2;
    case USER_ROLE = 3;

    public function isSuperAdmin(int $role): bool
    {
        return match ($role) {
            (self::SUPER_ADMIN_ROLE)->value => TRUE,
            default => FALSE
        };
    }

    public function isAdmin(int $role): bool
    {
        return match ($role) {
            (self::ADMIN_ROLE)->value => TRUE,
            default => FALSE
        };
    }

    public function isUser(int $role): bool
    {
        return match ($role) {
            (self::USER_ROLE)->value => TRUE,
            default => FALSE
        };
    }


    public function checkValidRole(int $role): bool
    {
        return
            $this->isSuperAdmin($role) ||
            $this->isAdmin($role) ||
            $this->isUser($role);
    }
}
