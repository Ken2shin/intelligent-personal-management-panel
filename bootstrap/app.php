<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORREGIDO: Se eliminó \Livewire\Livewire::class de aquí.
        // Livewire 3 ya se carga automáticamente, no necesitas agregarlo manualmente.
        $middleware->web(append: [
            // Aquí puedes poner otros middlewares si los necesitas en el futuro,
            // pero por ahora déjalo vacío o con los que realmente uses.
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();