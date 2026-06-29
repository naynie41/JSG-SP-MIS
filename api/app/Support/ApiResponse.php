<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * Central factory for the SP-MIS API response envelopes.
 *
 * Every endpoint MUST return through here so the success/error shapes stay
 * identical across the whole API (see docs/CONVENTIONS.md §4). JSON keys are
 * snake_case to match the database and Eloquent models.
 *
 *   success: { "data": {...}, "meta": {...} }
 *   error:   { "error": { "code", "message", "details": [ {"field","message"} ] } }
 */
final class ApiResponse
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public static function success(mixed $data = null, array $meta = [], int $status = 200): JsonResponse
    {
        $payload = ['data' => $data];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    /**
     * @param  array<int, array<string, mixed>>  $details
     */
    public static function error(string $code, string $message, array $details = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
        ], $status);
    }
}
