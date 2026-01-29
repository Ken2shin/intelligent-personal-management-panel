<?php

namespace App\Providers;

use App\Events\TareaCreated;
use App\Events\TareaUpdated;
use App\Events\TransactionCreated;
use App\Listeners\SendTaskReminder;
use App\Listeners\SendTransactionNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Eventos de Tareas -> Enviar recordatorios
        TareaCreated::class => [
            SendTaskReminder::class,
        ],
        TareaUpdated::class => [
            SendTaskReminder::class,
        ],

        // Eventos de Transacciones -> Enviar notificaciones
        TransactionCreated::class => [
            SendTransactionNotification::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
