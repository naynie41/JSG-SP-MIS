<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    /**
     * Liveness/readiness probe.
     *
     * Confirms the API is up, the database is reachable, and the PostGIS
     * extension is installed. Returns 200 only when the database is healthy,
     * otherwise 503 with the standard error envelope.
     */
    public function show(): JsonResponse
    {
        $database = $this->checkDatabase();

        if ($database['connected'] !== true) {
            return ApiResponse::error(
                code: 'SERVICE_UNHEALTHY',
                message: 'One or more dependencies are unavailable.',
                details: [['field' => 'database', 'message' => 'The database connection failed.']],
                status: 503,
            );
        }

        return ApiResponse::success([
            'status' => 'ok',
            'service' => config('app.name'),
            'environment' => config('app.env'),
            'time' => now()->toIso8601String(),
            'checks' => [
                'database' => $database,
            ],
        ]);
    }

    /**
     * @return array{connected: bool, driver: string, postgis: array{enabled: bool, version: string|null}}
     */
    private function checkDatabase(): array
    {
        $driver = (string) config('database.default');

        try {
            DB::connection()->getPdo();

            $postgis = DB::selectOne("SELECT extversion FROM pg_extension WHERE extname = 'postgis'");

            return [
                'connected' => true,
                'driver' => $driver,
                'postgis' => [
                    'enabled' => $postgis !== null,
                    'version' => $postgis->extversion ?? null,
                ],
            ];
        } catch (Throwable $e) {
            report($e);

            return [
                'connected' => false,
                'driver' => $driver,
                'postgis' => ['enabled' => false, 'version' => null],
            ];
        }
    }
}
