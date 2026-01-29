<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request to ensure HTTPS in production.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo forzar HTTPS en producción
        if (!app()->environment('production')) {
            return $next($request);
        }

        // ============================================================
        // 1. Detectar si ya estamos en HTTPS (por varios métodos)
        // ============================================================
        $isSecure = $request->isSecure() || 
                   $request->header('X-Forwarded-Proto') === 'https' ||
                   $request->header('X-Forwarded-Protocol') === 'https' ||
                   $request->header('CF-Visitor') !== null || // Cloudflare always HTTPS to us
                   $request->header('X-Forwarded-Ssl') === 'on' ||
                   $request->header('X-Forwarded-Scheme') === 'https';

        // Si ya estamos en HTTPS, continuar normalmente
        if ($isSecure) {
            return $next($request);
        }

        // ============================================================
        // 2. Si NO estamos en HTTPS, redirigir
        // ============================================================
        // Construir la URL HTTPS manualmente para mayor control
        $url = 'https://' . $request->getHost() . $request->getRequestUri();

        return redirect($url, 308); // 308 Permanent Redirect (mantiene el método HTTP)
    }
}
