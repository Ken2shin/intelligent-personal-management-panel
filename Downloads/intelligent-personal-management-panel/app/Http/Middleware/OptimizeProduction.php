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

        // Agregar headers de seguridad
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'DENY');
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Compression (si no está comprimido)
        if (
            function_exists('gzencode') &&
            strlen($response->getContent()) > 1024 &&
            stripos($response->header('Content-Encoding'), 'gzip') === false
        ) {
            $response->header('Content-Encoding', 'gzip');
            $response->setContent(gzencode($response->getContent(), 9));
        }

        // Cache headers para recursos estáticos
        if ($this->isStaticAsset($request->path())) {
            $response->header('Cache-Control', 'public, max-age=31536000, immutable');
        } else {
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
