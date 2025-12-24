<?php
namespace App\Support;

use Illuminate\Support\Facades\Auth;

class SidebarMenu
{
    public static function get(): array
    {
        $menus = config('sidebar');

        return collect($menus)
            ->filter(fn ($menu) => self::can($menu))
            ->map(function ($menu) {
                if (!empty($menu['children'])) {
                    $menu['children'] = collect($menu['children'])
                        ->filter(fn ($child) => self::can($child))
                        ->values()
                        ->toArray();
                }
                return $menu;
            })
            ->values()
            ->toArray();
    }

    protected static function can(array $item): bool
    {
        if (!isset($item['permission']) || !$item['permission']) {
            return true;
        }

        return Auth::user()?->can($item['permission']) ?? false;
    }
}
