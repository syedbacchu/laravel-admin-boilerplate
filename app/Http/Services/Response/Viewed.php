<?php

namespace App\Http\Services\Response;

class Viewed
{
    protected static array $views = [
        'auth' => [
            'login' => 'auth.login',
            'forgot' => 'auth.forgot_password',
            'reset' => 'auth.reset_password',
        ],
        'slider' => [
            'list'  => 'admin.app.app_slider.index',
            'create' => 'admin.app.app_slider.create',
            'edit'   => 'admin.app.app_slider.edit',
        ],
        'user' => [
            'index'  => 'admin.user.index',
            'create' => 'admin.user.create',
        ],
        'file' => [
            'list_data'  => 'admin.file_manager.list',
            'list'  => 'admin.file_manager.index',
            'create' => 'admin.file_manager.create',
            'partial_data' => 'admin.file_manager.file_data',
        ],
        'custom' => [
            'index'  => 'admin.custom_fields.index',
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
