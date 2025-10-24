<?php

namespace App\Http\Services\Response;

class Viewed
{
    protected static array $views = [
        'slider' => [
            'list'  => 'admin.app.app_slider.index',
            'create' => 'admin.app.app_slider.create',
            'edit'   => 'admin.app.app_slider.edit',
        ],
        'user' => [
            'index'  => 'admin.user.index',
            'create' => 'admin.user.create',
        ],
    ];

    /**
     * Get a view path by group and key.
     */
    public static function get(string $group, string $key, ?string $default = null): ?string
    {
        return static::$views[$group][$key] ?? $default;
    }

    /**
     * Get all view paths (optional)
     */
    public static function all(): array
    {
        return static::$views;
    }
}
