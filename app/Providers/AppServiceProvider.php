<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Configuración de Seguridad Militar y Optimización.
     */
    public function boot(): void
    {
        // 1. SEGURIDAD DE TRANSPORTE (HTTPS Forzado)
        // Evita el robo de cookies y datos en redes públicas.
        if ($this->app->environment('production')) {
            // Forzar HTTPS en producción
            URL::forceScheme('https');
            
            // Configurar URLs confiables para que el proxy HTTP forwarding funcione
            URL::forceRootUrl(env('APP_URL', 'https://localhost'));
        }

        // 2. ESTABILIDAD DE FECHAS (Anti-Bugs Lógicos)
        // Usar fechas inmutables previene que las fechas cambien por error
        // al hacer cálculos (ej: sumar días), lo cual es vital en reportes financieros.
        Date::use(CarbonImmutable::class);

        // 3. SEGURIDAD OPERATIVA DE BASE DE DATOS (Anti-Borrado Accidental)
        // Impide ejecutar comandos como 'migrate:fresh' o 'db:wipe' en Producción.
        // Esto te salva de borrar la base de datos de tus usuarios por error.
        DB::prohibitDestructiveCommands($this->app->isProduction());

        // 4. COMPATIBILIDAD DE BASE DE DATOS
        // Previene errores de índice en MySQL/MariaDB antiguos o limitados.
        Schema::defaultStringLength(191);

        // 5. POLÍTICA DE CONTRASEÑAS BLINDADA (NIST Standard)
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->mixedCase()
                       ->numbers()
                       ->symbols()
                       ->uncompromised() // <--- Rechaza contraseñas filtradas en hackeos reales.
                : $rule;
        });

        // 6. RENDIMIENTO Y CALIDAD DE CÓDIGO (Strict Mode)
        // En desarrollo: Te avisa si haces consultas lentas (N+1) o asignaciones inseguras.
        // En producción: Se desactiva para garantizar que la web nunca se caiga por una advertencia.
        Model::shouldBeStrict(! $this->app->isProduction());
    }
}
