<?php

//应用菜单。

return [
    '应用信息' => [
        'desc' => [
            'name' => '应用简介',
            'logo' => 'ion-ios-bookmarks-outline',
            'url' => '/base/backend/app',
            'require_auth' => '0',
        ],
        'manual' => [
            'name' => '使用手册',
            'logo' => 'ion-ios-bookmarks-outline',
            'url' => '/base/backend/app/manual',
            'require_auth' => '0',
        ],
    ],
    '校园卡' => [
        'list' => [
            'name' => '卡列表',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ],
        'setting' => [
            'name' => '设置',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/setting',
            'require_auth' => '0',
        ],
        /*'list' => [
            'name' => '卡管理',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/default',
            'require_auth' => '0',
        ],
        'delivery' => [
            'name' => '卡投放',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/default/delivery',
            'require_auth' => '0',
        ],*/
    ],
    '正式卡' => [
        'category' => [
            'name' => '卡类别',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ],
        'list' => [
            'name' => '列表页',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ],
        'setting' => [
            'name' => '卡设置',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ]
    ],
    '临时卡' => [
        'list' => [
            'name' => '列表页',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ],
        'setting' => [
            'name' => '卡设置',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ]
    ],
    '领卡地址' => [
        'setting' => [
            'name' => '领卡地址',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ]
    ],
    '数据统计' => [
        'setting' => [
            'name' => '数据统计',
            'logo' => 'ion-navicon',
            'url' => '/alicard/backend/list',
            'require_auth' => '0',
        ]
    ]
];
