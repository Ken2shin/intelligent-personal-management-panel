<?php

namespace App\Listeners;

use App\Events\TareaCreated;
use App\Events\TareaUpdated;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\AppleNotificationService;
use App\Services\SecurityService;
use Illuminate\Support\Facades\Log;

class SendTaskReminder
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
     * Handle event de tarea creada/actualizada
     */
    public function handle(TareaCreated|TareaUpdated $event): void
    {
        try {
            $tarea = $event->tarea;
            $user = $tarea->user;

            if (!$user || !$tarea->fecha_limite) {
                return;
            }

            // Enviar notificación en tiempo real (WebSocket)
            $this->notificationService->sendTaskReminder(
                $user->id,
                [
                    'id' => $tarea->id,
                    'titulo' => $tarea->titulo,
                    'fecha_limite' => $tarea->fecha_limite,
                    'prioridad' => $tarea->prioridad,
                ]
            );

            // Obtener dispositivos iOS del usuario
            $devices = $user->iosDevices()->pluck('device_token')->toArray();

            // Enviar push notification a iOS
            if (!empty($devices)) {
                foreach ($devices as $deviceToken) {
                    $this->appleService->sendTaskReminderPush($deviceToken, [
                        'id' => $tarea->id,
                        'titulo' => $tarea->titulo,
                        'fecha_limite' => $tarea->fecha_limite->toDateTimeString(),
                        'prioridad' => $tarea->prioridad,
                    ]);
                }
            }

            // Log de auditoría
            $this->securityService->auditLog(
                'task_reminder_sent',
                'tarea',
                $tarea->id,
                ['user_id' => $user->id],
                $user->id
            );

            Log::info("Task reminder sent", ['tarea_id' => $tarea->id, 'user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('SendTaskReminder error: ' . $e->getMessage());
        }
    }
}
