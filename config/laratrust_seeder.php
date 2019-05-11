<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'dashboard' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'vendor_categories' => 'c,r,u,d',
            'products' => 'c,r,u,d',
            'settings' => 'c,r,u,d',
            'orders' => 'c,r,u,d'
        ],
        'administrator' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'user' => [
            'profile' => 'r,u'
        ],
    ],
    'permission_structure' => [
//        'cru_user' => [
//            'profile' => 'c,r,u'
//        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
