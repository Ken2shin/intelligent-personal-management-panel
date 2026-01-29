<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AppleNotificationService
{
    private string $baseUrl = 'https://api.push.apple.com';
    private string $keyId;
    private string $teamId;
    private string $bundleId;
    private string $certificate;
    private bool $isProduction;

    public function __construct()
    {
        $this->keyId = config('services.apns.key_id', '');
        $this->teamId = config('services.apns.team_id', '');
        $this->bundleId = config('services.apns.bundle_id', '');
        $this->certificate = config('services.apns.certificate', '');
        $this->isProduction = config('services.apns.production', false);
    }

    /**
     * Generar JWT para APNs
     */
    private function generateJWT(): string
    {
        $now = time();
        $header = ['alg' => 'ES256', 'kid' => $this->keyId, 'typ' => 'JWT'];
        $claims = [
            'iss' => $this->teamId,
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $header64 = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $claims64 = rtrim(strtr(base64_encode(json_encode($claims)), '+/', '-_'), '=');
        $message = "{$header64}.{$claims64}";

        // Firmar con el certificado P8
        $privateKey = openssl_pkey_get_private($this->certificate);
        openssl_sign($message, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $signature64 = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "{$message}.{$signature64}";
    }

    /**
     * Enviar notificación push a iOS
     */
    public function sendPushNotification(string $deviceToken, array $payload): bool
    {
        try {
            $jwt = $this->generateJWT();
            $environment = $this->isProduction ? '3' : '1';
            $url = "{$this->baseUrl}:443/3/device/{$deviceToken}";

            $payload['aps'] = $payload['aps'] ?? [
                'alert' => [
                    'title' => $payload['title'] ?? '',
                    'body' => $payload['body'] ?? '',
                ],
                'sound' => 'default',
                'badge' => 1,
                'mutable-content' => 1,
                'category' => $payload['category'] ?? '',
            ];

            $response = Http::withHeaders([
                'apns-topic' => $this->bundleId,
                'authorization' => "bearer {$jwt}",
                'apns-priority' => '10',
                'apns-push-type' => 'alert',
            ])
            ->timeout(10)
            ->post($url, $payload);

            if ($response->successful()) {
                Log::info("Push notification sent to iOS device");
                return true;
            }

            Log::error("Push notification failed", ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (Exception $e) {
            Log::error('APNs Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de recordatorio de tarea a iOS
     */
    public function sendTaskReminderPush(string $deviceToken, array $task): bool
    {
        return $this->sendPushNotification($deviceToken, [
            'title' => 'Recordatorio de Tarea',
            'body' => "No olvides: {$task['titulo']}",
            'category' => 'task_reminder',
            'custom' => [
                'task_id' => $task['id'],
                'priority' => $task['prioridad'],
                'due_at' => $task['fecha_limite'],
            ],
        ]);
    }

    /**
     * Enviar notificación de transacción financiera a iOS
     */
    public function sendTransactionPush(string $deviceToken, array $transaction): bool
    {
        $typeLabel = match($transaction['type']) {
            'income' => 'Ingreso',
            'expense' => 'Gasto',
            'transfer' => 'Transferencia',
            default => 'Transacción',
        };

        return $this->sendPushNotification($deviceToken, [
            'title' => $typeLabel,
            'body' => "{$transaction['amount']} {$transaction['currency']} - {$transaction['category']}",
            'category' => 'transaction',
            'custom' => [
                'transaction_id' => $transaction['id'],
                'type' => $transaction['type'],
                'amount' => $transaction['amount'],
                'currency' => $transaction['currency'],
            ],
        ]);
    }

    /**
     * Enviar notificación silenciosa (background)
     */
    public function sendSilentNotification(string $deviceToken, array $data): bool
    {
        try {
            $jwt = $this->generateJWT();
            $url = "{$this->baseUrl}:443/3/device/{$deviceToken}";

            $response = Http::withHeaders([
                'apns-topic' => $this->bundleId,
                'authorization' => "bearer {$jwt}",
                'apns-priority' => '5',
                'apns-push-type' => 'background',
            ])
            ->timeout(10)
            ->post($url, [
                'aps' => [
                    'content-available' => 1,
                ],
                'data' => $data,
            ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Silent Push Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar a múltiples dispositivos
     */
    public function sendToMultipleDevices(array $deviceTokens, array $payload): array
    {
        $results = [];
        foreach ($deviceTokens as $token) {
            $results[$token] = $this->sendPushNotification($token, $payload);
        }
        return $results;
    }
}
