<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\OptimizeProduction;
use App\Http\Middleware\ForceHttps;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trusted Proxies para producción (DEBE IR PRIMERO)
        // Importante para servicios como Vercel, Heroku, Cloudflare, etc.
        // Permite que los headers X-Forwarded-* sean confiables desde cualquier proxy
        $middleware->trustProxies(at: ['*']);

        // Registrar middlewares de seguridad y optimización en orden correcto
        $middleware->web(append: [
            ForceHttps::class,              // Forzar HTTPS (debe ir antes de OptimizeProduction)
            OptimizeProduction::class,      // Headers de seguridad y optimización
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
