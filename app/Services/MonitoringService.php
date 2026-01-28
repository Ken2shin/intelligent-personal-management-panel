<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class MonitoringService
{
    /**
     * Registrar métrica de query
     */
    public static function logQuery(string $name, float $duration, bool $slow = false)
    {
        if ($slow) {
            Log::warning('Slow Query', [
                'name' => $name,
                'duration_ms' => $duration,
                'timestamp' => now(),
            ]);
        }

        // Guardar en Redis para análisis en tiempo real
        Cache::increment("queries:{$name}:count");
        Cache::increment("queries:{$name}:total_time", (int)$duration);
    }

    /**
     * Registrar errores
     */
    public static function logError(Exception $exception, array $context = [])
    {
        Log::error($exception->getMessage(), array_merge([
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ], $context));

        // Alertar si hay muchos errores
        $errorKey = 'errors:count:' . now()->format('H:i');
        $count = Cache::increment($errorKey);
        Cache::expire($errorKey, 3600);

        if ($count > 100) {
            Log::critical('High error rate detected', ['count' => $count]);
        }
    }

    /**
     * Obtener estadísticas de rendimiento
     */
    public static function getPerformanceStats(): array
    {
        try {
            $queryStats = DB::select("
                SELECT 
                    query,
                    calls,
                    total_time,
                    mean_time,
                    max_time
                FROM pg_stat_statements
                ORDER BY total_time DESC
                LIMIT 10
            ");

            return [
                'top_queries' => $queryStats,
                'connection_count' => DB::select("SELECT count(*) as count FROM pg_stat_activity")[0]->count ?? 0,
                'cache_hits' => Cache::getConnection()->info()['stats']['keyspace_hits'] ?? 0,
            ];
        } catch (Exception $e) {
            Log::error('Failed to get performance stats', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Verificar salud del sistema
     */
    public static function healthCheck(): array
    {
        $health = [
            'timestamp' => now(),
            'database' => 'unknown',
            'redis' => 'unknown',
            'memory' => 'unknown',
        ];

        // Verificar BD
        try {
            DB::connection()->getPdo();
            $health['database'] = 'ok';
        } catch (Exception $e) {
            $health['database'] = 'error';
            Log::error('Database health check failed', ['error' => $e->getMessage()]);
        }

        // Verificar Redis
        try {
            Cache::getConnection()->ping();
            $health['redis'] = 'ok';
        } catch (Exception $e) {
            $health['redis'] = 'error';
            Log::error('Redis health check failed', ['error' => $e->getMessage()]);
        }

        // Verificar memoria
        $memory = memory_get_usage() / 1024 / 1024;
        $limit = ini_get('memory_limit');
        $health['memory'] = $memory . 'MB / ' . $limit;
        $health['memory_status'] = $memory > 256 ? 'warning' : 'ok';

        return $health;
    }
}
