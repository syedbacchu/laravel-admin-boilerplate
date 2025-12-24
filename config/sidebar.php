<?php


return [
    [
        'key' => 'dashboard',
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'dashboard',
        'permission' => null,
    ],
    [
        'key' => 'users',
        'label' => 'User Management',
        'icon' => 'user',
        'permission' => null,
        'children' => [
            [
                'label' => 'User List',
                'route' => 'user.list',
                'permission' => null,
            ],
            [
                'label' => 'User Create',
                'route' => 'user.create',
                'permission' => null,
            ],
        ],
    ],
    [
        'key' => 'app',
        'label' => 'App Setup',
        'icon' => 'app',
        'permission' => null,
        'children' => [
            [
                'label' => 'Slider',
                'route' => 'appSlider.list',
                'permission' => null,
            ],
        ],
    ],
    [
        'key' => 'role',
        'label' => 'Role Management',
        'icon' => 'role',
        'permission' => null,
        'children' => [
            [
                'label' => 'Web Role',
                'route' => 'role.index',
                'permission' => null,
            ],
            [
                'label' => 'Web Permissions',
                'route' => 'role.webPermission',
                'permission' => null,
            ],
            [
                'label' => 'Api Role',
                'route' => 'role.apiRole',
                'permission' => null,
            ],
            [
                'label' => 'Api Permissions',
                'route' => 'role.apiPermission',
                'permission' => null,
            ],
        ],
    ],

    [
        'key' => 'faq',
        'label' => 'FAQ',
        'icon' => 'faq',
        'permission' => null,
        'children' => [
            [
                'label' => 'Category',
                'route' => 'faqCategory.list',
                'permission' => null,
            ],
            [
                'label' => 'FAQ',
                'route' => 'faq.list',
                'permission' => null,
            ],
        ],
    ],

    [
        'key' => 'custom_fields',
        'label' => 'Custom Fields',
        'route' => 'customField.index',
        'icon' => 'custom-fields',
        'permission' => null,
    ],

    [
        'key' => 'settings',
        'label' => 'Settings',
        'icon' => 'settings',
        'permission' => null,
        'children' => [
            [
                'label' => 'General Settings',
                'route' => 'settings.generalSetting',
                'permission' => null,
            ],
            [
                'label' => 'Settings Fields',
                'route' => 'settings.fields.index',
                'permission' => null,
            ],
        ],
    ],
    [
        'key' => 'audit',
        'label' => 'Audit Logs',
        'icon' => 'audit',
        'permission' => null,
        'children' => [
            [
                'label' => 'Logs',
                'route' => 'audit.logs',
                'permission' => null,
            ],
            [
                'label' => 'Settings',
                'route' => 'audit.settings',
                'permission' => null,
            ],
        ],
    ],
    [
        'key' => 'logs',
        'label' => 'Error Logs',
        'route' => 'errorLog',
        'icon' => 'logs',
        'permission' => null,
    ],
];
