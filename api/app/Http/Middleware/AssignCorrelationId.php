<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Assigns a correlation/request id to each request (honouring an incoming
 * X-Correlation-Id / X-Request-Id header, otherwise generating one), exposes it
 * on the request for the audit log, and echoes it back in the response header.
 */
class AssignCorrelationId
{
    public function handle(Request $request, Closure $next): Response
    {
        $incoming = $request->headers->get('X-Correlation-Id')
            ?? $request->headers->get('X-Request-Id');

        $correlationId = $this->normalize($incoming) ?? (string) Str::uuid();

        $request->attributes->set('correlation_id', $correlationId);

        $response = $next($request);
        $response->headers->set('X-Correlation-Id', $correlationId);

        return $response;
    }

    /**
     * Only accept a well-formed UUID from the client; otherwise generate one.
     */
    private function normalize(?string $value): ?string
    {
        return $value !== null && Str::isUuid($value) ? $value : null;
    }
}
