<?php

$menuItems = [
    [
        'items' => [
            [
                'title' => 'Dashboard',
                'icon' => 'ri-home-smile-line',
                'route' => 'dashboard',
                'active' => 'dashboard',
                'submenu' => []
            ]
        ]
    ],
    [
        'header' => 'User Managements',
        'items' => [
            [
                'title' => 'Roles',
                'icon' => 'ri-shield-user-line',
                'route' => 'roles.index',
                'active' => 'roles.*',
                'submenu' => []
            ],
            [
                'title' => 'Users',
                'icon' => 'ri-user-line',
                'route' => 'users.index',
                'active' => 'users.*',
                'submenu' => []
            ]
        ]
    ],
    [
        'header' => 'Master Data',
        'items' => [
            [
                'title' => 'Designations',
                'icon' => 'ri-file-list-3-line',
                'route' => 'designations.index',
                'active' => 'designations.*',
                'submenu' => []
            ],
            [
                'title' => 'Employees',
                'icon' => 'ri-user-3-line',
                'route' => 'employees.index',
                'active' => 'employees.*',
                'submenu' => []
            ],
        ]
    ],
    [
        'header' => 'Settings',
        'items' => [
            [
                'title' => 'Profile',
                'icon' => 'ri-settings-4-line',
                'route' => 'profile.edit',
                'active' => 'profile.*',
                'submenu' => []
            ]
        ]
    ]
];

return $menuItems;
