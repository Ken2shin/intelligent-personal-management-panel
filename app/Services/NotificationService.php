<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class NotificationService
{
    private string $baseUrl;
    private string $wsUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.notification.url', 'http://notification-service:8080');
        $this->wsUrl = config('services.notification.ws_url', 'wss://localhost/ws');
    }

    /**
     * Enviar notificación en tiempo real
     */
    public function sendRealtime(string $userId, string $type, array $data): bool
    {
        try {
            $response = Http::timeout(5)
                ->post("{$this->baseUrl}/api/v1/notify", [
                    'user_id' => $userId,
                    'type' => $type,
                    'data' => $data,
                    'timestamp' => now()->toIso8601String(),
                ]);

            if ($response->successful()) {
                Log::info("Notification sent to user {$userId}", ['type' => $type]);
                return true;
            }

            Log::error("Notification failed for user {$userId}", ['status' => $response->status()]);
            return false;
        } catch (Exception $e) {
            Log::error('Notification Service Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de recordatorio de tarea
     */
    public function sendTaskReminder(string $userId, array $task, int $minutesBefore = 30): bool
    {
        return $this->sendRealtime($userId, 'task_reminder', [
            'task_id' => $task['id'],
            'task_title' => $task['titulo'],
            'due_at' => $task['fecha_limite'],
            'priority' => $task['prioridad'],
            'minutes_before' => $minutesBefore,
            'message' => "Recordatorio: {$task['titulo']} vence en {$minutesBefore} minutos",
        ]);
    }

    /**
     * Enviar notificación de transacción financiera
     */
    public function sendTransactionNotification(string $userId, array $transaction): bool
    {
        return $this->sendRealtime($userId, 'transaction', [
            'transaction_id' => $transaction['id'],
            'type' => $transaction['type'], // income, expense, transfer
            'amount' => $transaction['amount'],
            'currency' => $transaction['currency'] ?? 'USD',
            'category' => $transaction['category'],
            'message' => "{$transaction['type']}: {$transaction['amount']} {$transaction['currency']} en {$transaction['category']}",
        ]);
    }

    /**
     * Enviar notificación de hábito completado
     */
    public function sendHabitNotification(string $userId, array $habit): bool
    {
        return $this->sendRealtime($userId, 'habit_reminder', [
            'habit_id' => $habit['id'],
            'habit_name' => $habit['nombre'],
            'frequency' => $habit['frecuencia'],
            'message' => "Recordatorio: Es hora de completar tu hábito: {$habit['nombre']}",
        ]);
    }

    /**
     * Enviar notificación broadcast a múltiples usuarios
     */
    public function broadcastNotification(array $userIds, string $type, array $data): array
    {
        $results = [];
        foreach ($userIds as $userId) {
            $results[$userId] = $this->sendRealtime($userId, $type, $data);
        }
        return $results;
    }

    /**
     * Programar notificación para enviar después
     */
    public function scheduleNotification(string $userId, string $type, array $data, \DateTime $sendAt): bool
    {
        try {
            $response = Http::timeout(5)
                ->post("{$this->baseUrl}/api/v1/schedule-notification", [
                    'user_id' => $userId,
                    'type' => $type,
                    'data' => $data,
                    'send_at' => $sendAt->toIso8601String(),
                ]);

            if ($response->successful()) {
                Log::info("Notification scheduled for user {$userId}");
                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Schedule Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener URL para conectar WebSocket
     */
    public function getWebSocketUrl(string $userId, string $token): string
    {
        return "{$this->wsUrl}?user_id={$userId}&token={$token}";
    }

    /**
     * Verificar conexión del servicio
     */
    public function healthCheck(): bool
    {
        try {
            $response = Http::timeout(3)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (Exception $e) {
            Log::warning('Notification Service health check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas de notificaciones
     */
    public function getStats(string $userId): array
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->baseUrl}/api/v1/stats/{$userId}");

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (Exception $e) {
            Log::error('Get stats error: ' . $e->getMessage());
            return [];
        }
    }
}
