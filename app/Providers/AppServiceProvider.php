<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Model;

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
     * Blindaje de seguridad y optimización de alto tráfico.
     */
    public function boot(): void
    {
        // 1. SEGURIDAD DE TRANSPORTE (Anti-Man-in-the-Middle)
        // Fuerza a que TODA la comunicación sea encriptada (HTTPS) en producción.
        // Esto arregla tu error de "Sitio no seguro" y protege los datos en tránsito.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 2. ESTABILIDAD DE BASE DE DATOS (Anti-DoS y Errores de Migración)
        // Limita la longitud de los índices de cadenas. Esto previene errores en
        // bases de datos antiguas y optimiza el tamaño de los índices para búsquedas más rápidas.
        Schema::defaultStringLength(191);

        // 3. POLÍTICA DE CONTRASEÑAS GLOBAL (Anti-Fuerza Bruta)
        // Define que cualquier contraseña nueva en el sistema DEBE ser compleja.
        // En producción exige: Mínimo 8 caracteres, letras, números y símbolos.
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->mixedCase()->numbers()->symbols() // Producción: Máxima seguridad
                : $rule; // Desarrollo: Solo longitud mínima para ir rápido
        });

        // 4. RENDIMIENTO Y SEGURIDAD DE MODELOS (Anti-N+1 Queries)
        // "Strict Mode" te avisa en desarrollo si estás programando mal (haciendo consultas lentas).
        // En producción se desactiva automáticamente para no romper la app si hay un error leve,
        // garantizando que el tráfico alto fluya sin interrupciones.
        Model::shouldBeStrict(! $this->app->isProduction());
    }
}