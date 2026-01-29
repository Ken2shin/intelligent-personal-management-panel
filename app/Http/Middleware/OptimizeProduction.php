<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OptimizeProduction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // ============================================================
        // 1. HEADERS DE SEGURIDAD HTTPS/TLS (HSTS)
        // ============================================================
        // Strict-Transport-Security obliga al navegador a usar HTTPS siempre
        $response->header('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        
        // Otros headers de seguridad críticos
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'DENY');
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // ============================================================
        // 2. COMPRESSION (Mejora rendimiento y velocidad)
        // ============================================================
        if (
            function_exists('gzencode') &&
            strlen($response->getContent()) > 1024 &&
            stripos($response->header('Content-Encoding'), 'gzip') === false
        ) {
            $response->header('Content-Encoding', 'gzip');
            $response->setContent(gzencode($response->getContent(), 9));
        }

        // ============================================================
        // 3. CACHE HEADERS (Optimización de rendimiento)
        // ============================================================
        if ($this->isStaticAsset($request->path())) {
            // Recursos estáticos: cachear por 1 año
            $response->header('Cache-Control', 'public, max-age=31536000, immutable');
        } else {
            // Contenido dinámico: no cachear
            $response->header('Cache-Control', 'private, max-age=0, must-revalidate');
        }

        return $response;
    }

    /**
     * Verificar si es un recurso estático.
     */
    private function isStaticAsset(string $path): bool
    {
        return preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/', $path);
    }
}
