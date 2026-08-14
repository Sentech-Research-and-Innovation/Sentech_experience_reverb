<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // The tx-platform-mobile app (Capacitor/Ionic) shares these two routes with
        // the web SPA but has no CSRF token mechanism - it authenticates via a
        // Sanctum bearer token issued from the login response, not a session
        // cookie. Every other "web" route stays CSRF-protected.
        'login',
        'register',
    ];
}
