<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Audit\Models\AuditLog;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Models\DashboardSnapshot;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * Health, readiness and operational metrics (NFR-AVAIL-01).
 *
 * `/up` (framework) is the bare liveness probe. This readiness probe additionally
 * confirms the critical dependencies — database (+ PostGIS) and cache — are usable,
 * and surfaces the statelessness configuration (session/cache/queue drivers) so a
 * load balancer / orchestrator can verify a node is fit to serve. Metrics are a
 * small, non-PII operational snapshot for monitoring, and are permission-gated.
 */
class HealthController extends Controller
{
    /** Readiness probe: 200 only when the database and cache are healthy. */
    public function show(): JsonResponse
    {
        $database = $this->checkDatabase();
        $cache = $this->checkCache();

        $healthy = $database['connected'] === true && $cache['ok'] === true;

        $payload = [
            'status' => $healthy ? 'ok' : 'degraded',
            'service' => config('app.name'),
            'environment' => config('app.env'),
            'time' => now()->toIso8601String(),
            'checks' => ['database' => $database, 'cache' => $cache],
            // Statelessness posture (NFR-SCAL-01): the app is horizontally scalable
            // only when session + cache are shared stores and the queue is durable.
            'runtime' => [
                'session_driver' => config('session.driver'),
                'cache_store' => config('cache.default'),
                'queue_connection' => config('queue.default'),
                'stateless' => $this->isStateless(),
            ],
        ];

        return $healthy
            ? ApiResponse::success($payload)
            : ApiResponse::error('SERVICE_UNHEALTHY', 'One or more dependencies are unavailable.', [
                ['field' => 'database', 'message' => $database['connected'] ? 'ok' : 'unavailable'],
                ['field' => 'cache', 'message' => $cache['ok'] ? 'ok' : 'unavailable'],
            ], 503);
    }

    /**
     * Operational metrics for monitoring (permission-gated; no PII). Surfaces the
     * signals ops actually alerts on: last successful backup, dashboard-snapshot
     * freshness, and coarse table volumes.
     */
    public function metrics(): JsonResponse
    {
        $lastBackup = AuditLog::query()->where('action', 'backup.created')->latest('created_at')->first();
        $lastSnapshot = DashboardSnapshot::query()->latest('computed_at')->first();

        return ApiResponse::success([
            'time' => now()->toIso8601String(),
            'backups' => [
                'last_success_at' => $lastBackup?->created_at?->toIso8601String(),
                'age_hours' => $lastBackup?->created_at ? round($lastBackup->created_at->diffInMinutes(now()) / 60, 1) : null,
                'rpo_hours' => round((int) config('backup.rpo_minutes', 1440) / 60, 1),
            ],
            'dashboard_snapshots' => [
                'last_computed_at' => $lastSnapshot?->computed_at?->toIso8601String(),
                'stale_minutes' => $lastSnapshot?->computed_at ? $lastSnapshot->computed_at->diffInMinutes(now()) : null,
            ],
            'volumes' => [
                'beneficiaries' => Beneficiary::query()->withoutGlobalScopes()->count(),
                'benefits' => Benefit::query()->withoutGlobalScopes()->count(),
                'audit_entries' => AuditLog::query()->count(),
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
            $postgis = DB::connection()->getDriverName() === 'pgsql'
                ? DB::selectOne("SELECT extversion FROM pg_extension WHERE extname = 'postgis'")
                : null;

            return [
                'connected' => true,
                'driver' => $driver,
                'postgis' => ['enabled' => $postgis !== null, 'version' => $postgis->extversion ?? null],
            ];
        } catch (Throwable $e) {
            report($e);

            return ['connected' => false, 'driver' => $driver, 'postgis' => ['enabled' => false, 'version' => null]];
        }
    }

    /**
     * @return array{ok: bool, store: string}
     */
    private function checkCache(): array
    {
        $store = (string) config('cache.default');
        try {
            $sentinel = 'health:'.Str::uuid()->toString();
            Cache::put($sentinel, '1', Carbon::now()->addSeconds(10));
            $ok = Cache::get($sentinel) === '1';
            Cache::forget($sentinel);

            return ['ok' => $ok, 'store' => $store];
        } catch (Throwable $e) {
            report($e);

            return ['ok' => false, 'store' => $store];
        }
    }

    /** Whether session + cache are shared stores and the queue is durable (not sync). */
    private function isStateless(): bool
    {
        $shared = ['redis', 'memcached', 'dynamodb', 'database'];

        return in_array((string) config('session.driver'), $shared, true)
            && in_array((string) config('cache.default'), $shared, true)
            && config('queue.default') !== 'sync';
    }
}
