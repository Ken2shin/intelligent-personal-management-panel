<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
    ],

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'notice'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'notice'),
            'days' => 30,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Panel Inteligente',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'database' => [
            'driver' => 'single',
            'path' => storage_path('logs/database.log'),
            'level' => 'warning',
        ],

        'performance' => [
            'driver' => 'single',
            'path' => storage_path('logs/performance.log'),
            'level' => 'warning',
        ],

        'security' => [
            'driver' => 'single',
            'path' => storage_path('logs/security.log'),
            'level' => 'warning',
        ],

        'null' => [
            'driver' => 'null',
        ],

        'emergency' => [
            'path' => storage_path('logs/emergency.log'),
        ],
    ],
];
