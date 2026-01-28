<?php

return [
    'default' => env('QUEUE_CONNECTION', 'redis'),

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
        ],

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'connection' => null,
            'table' => 'jobs',
            'retry_after' => 90,
            'after_commit' => false,
        ],
    ],

    'batching' => [
        'database' => env('QUEUE_BATCHING_DATABASE', 'default'),
        'table' => 'job_batches',
    ],

    'failed' => [
        'database' => env('QUEUE_FAILED_DATABASE', 'default'),
        'table' => 'failed_jobs',
    ],
];
