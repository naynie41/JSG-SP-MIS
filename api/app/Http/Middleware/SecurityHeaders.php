<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies baseline HTTP security headers to every API response.
 *
 * The API serves JSON only, so a restrictive Content-Security-Policy is safe.
 * HSTS is only emitted over HTTPS so it does not break plain-HTTP local dev.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'no-referrer',
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'Cross-Origin-Resource-Policy' => 'same-site',
            'Content-Security-Policy' => "default-src 'none'; frame-ancestors 'none'",
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Never advertise the framework/server.
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
