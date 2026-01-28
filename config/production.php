<?php

/**
 * Configuración optimizada para producción
 * Alto tráfico y máxima robustez
 */

return [
    // Base de datos
    'database' => [
        'pool' => [
            'min' => env('DB_POOL_MIN', 10),
            'max' => env('DB_POOL_MAX', 50),
        ],
        'timeout' => 30000, // 30 segundos
        'statement_timeout' => 30000,
    ],

    // Redis (Cache)
    'redis' => [
        'timeout' => env('REDIS_TIMEOUT', 5),
        'retry' => env('REDIS_RETRY_INTERVAL', 100),
        'read_timeout' => env('REDIS_READ_TIMEOUT', 60),
    ],

    // Limpieza automática
    'cleanup' => [
        'soft_deletes' => true,
        'old_sessions' => true, // días
        'old_logs' => 30, // días
    ],

    // Rate limiting
    'rate_limit' => [
        'api' => '1000:60', // 1000 requests por minuto
        'login' => '5:15', // 5 intentos por 15 minutos
    ],

    // Compresión y caché
    'compression' => [
        'enabled' => true,
        'threshold' => 1024, // bytes
    ],

    // Session
    'session' => [
        'lifetime' => 43200, // 12 horas
        'expire_on_close' => false,
    ],

    // Jobs Queue
    'queue' => [
        'connection' => 'redis',
        'timeout' => 120,
        'max_attempts' => 3,
    ],
];
