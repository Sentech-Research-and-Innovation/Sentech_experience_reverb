<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $response->headers) {
            return $response;
        }

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'credentialless');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; ".
            "script-src 'self' 'unsafe-inline'; ".
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; ".
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; ".
            "img-src 'self' data: https:; ".
            "connect-src 'self' ws: wss:; ".
            "frame-ancestors 'self'"
        );
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
