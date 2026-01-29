<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        // Usamos headers->set() para asegurar compatibilidad con Symfony Response
        $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // ============================================================
        // 2. COMPRESSION (Mejora rendimiento y velocidad)
        // ============================================================
        
        // CORRECCIÓN CRÍTICA AQUÍ: Usamos headers->get() para leer
        $contentEncoding = $response->headers->get('Content-Encoding');

        if (
            function_exists('gzencode') &&
            !$request->expectsJson() && // Evitar comprimir JSON si no es necesario o causa doble compresión
            strlen($response->getContent()) > 1024 &&
            stripos((string)$contentEncoding, 'gzip') === false
        ) {
            $response->headers->set('Content-Encoding', 'gzip');
            $response->setContent(gzencode($response->getContent(), 9));
        }

        // ============================================================
        // 3. CACHE HEADERS (Optimización de rendimiento)
        // ============================================================
        if ($this->isStaticAsset($request->path())) {
            // Recursos estáticos: cachear por 1 año
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        } else {
            // Contenido dinámico: no cachear
            $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
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