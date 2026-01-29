<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Services\NotificationService;
use App\Services\AppleNotificationService;
use App\Services\SecurityService;
use Illuminate\Support\Facades\Log;

class SendTransactionNotification
{
    private NotificationService $notificationService;
    private AppleNotificationService $appleService;
    private SecurityService $securityService;

    public function __construct(
        NotificationService $notificationService,
        AppleNotificationService $appleService,
        SecurityService $securityService
    ) {
        $this->notificationService = $notificationService;
        $this->appleService = $appleService;
        $this->securityService = $securityService;
    }

    /**
     * Manejar evento de transacción creada
     */
    public function handle(TransactionCreated $event): void
    {
        try {
            $transaccion = $event->transaccion;
            $user = $transaccion->user;

            if (!$user) {
                return;
            }

            $transactionData = [
                'id' => $transaccion->id,
                'type' => $transaccion->tipo, // income, expense, transfer
                'amount' => $transaccion->monto,
                'currency' => $transaccion->moneda ?? 'USD',
                'category' => $transaccion->categoria,
            ];

            // Enviar notificación en tiempo real (WebSocket)
            $this->notificationService->sendTransactionNotification($user->id, $transactionData);

            // Obtener dispositivos iOS del usuario
            $devices = $user->iosDevices()->pluck('device_token')->toArray();

            // Enviar push notification a iOS
            if (!empty($devices)) {
                foreach ($devices as $deviceToken) {
                    $this->appleService->sendTransactionPush($deviceToken, $transactionData);
                }
            }

            // Log de auditoría de la transacción
            $this->securityService->auditLog(
                'transaction_created',
                'transaccion',
                $transaccion->id,
                [
                    'user_id' => $user->id,
                    'amount' => $transaccion->monto,
                    'type' => $transaccion->tipo,
                    'category' => $transaccion->categoria,
                ],
                $user->id
            );

            Log::info("Transaction notification sent", [
                'transaccion_id' => $transaccion->id,
                'user_id' => $user->id,
                'amount' => $transaccion->monto,
            ]);
        } catch (\Exception $e) {
            Log::error('SendTransactionNotification error: ' . $e->getMessage());
        }
    }
}
