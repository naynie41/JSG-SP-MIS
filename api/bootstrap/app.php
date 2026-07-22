<?php

declare(strict_types=1);

use App\Domain\Grievance\Jobs\EscalateOverdueGrievances;
use App\Domain\Privacy\Jobs\EnforceDataRetention;
use App\Domain\Referral\Jobs\EscalateOverdueReferrals;
use App\Domain\Reporting\Jobs\RefreshDashboardSnapshots;
use App\Domain\Reporting\Jobs\RunDueReportSchedules;
use App\Domain\Sync\Jobs\RunDueSyncConnectors;
use App\Http\Middleware\AssignCorrelationId;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\EnforceIdleTimeout;
use App\Http\Middleware\SecurityHeaders;
use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        // Referral SLA sweep (FR-REF-04/05): flag/escalate overdue referrals hourly.
        // Never auto-closes — it only escalates + notifies.
        $schedule->job(EscalateOverdueReferrals::class)->hourly()->withoutOverlapping();

        // Grievance SLA sweep (FR-GRM-03): flag/escalate overdue grievances hourly.
        // Never auto-closes — it only escalates + notifies.
        $schedule->job(EscalateOverdueGrievances::class)->hourly()->withoutOverlapping();

        // Dashboard summary refresh (FR-RPT-01/02): recompute scope snapshots so the
        // request path always reads a summary, never the raw ledger/registry.
        $schedule->job(RefreshDashboardSnapshots::class)->everyFifteenMinutes()->withoutOverlapping();

        // Scheduled reports (FR-RPT-04): generate + deliver every due schedule daily.
        $schedule->job(RunDueReportSchedules::class)->dailyAt('06:00')->withoutOverlapping();

        // External/source synchronization (FR-DSH-02): fan out an idempotent sync for
        // every enabled connector each hour. Per-connector jobs are unique so ticks
        // never overlap a running sync.
        $schedule->job(RunDueSyncConnectors::class)->hourly()->withoutOverlapping();

        // Data-retention enforcement (NFR-PRV-01): apply the DPO's retention policies
        // daily. A no-op unless retention is enabled + policies are configured; the
        // job is unique so overlapping ticks never double-process a cohort.
        $schedule->job(EnforceDataRetention::class)->dailyAt('02:00')->withoutOverlapping();

        // Encrypted offsite backup (NFR-AVAIL-01) — daily, defining the ~24h RPO.
        // Runs as a command (uses pg_dump / the DB file) rather than a queued job.
        $schedule->command('backup:run')->dailyAt('01:00')->withoutOverlapping()->runInBackground();

        // Weekly restore drill (NFR-AVAIL-01): prove the backups are recoverable
        // within the RTO. A failing drill exits non-zero for the monitor to catch.
        $schedule->command('backup:drill')->weekly()->sundays()->at('03:00')->withoutOverlapping();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Correlation id first (so it is available to everything), security
        // headers on every API response.
        $middleware->api(prepend: [
            AssignCorrelationId::class,
        ], append: [
            SecurityHeaders::class,
        ]);

        // Route middleware aliases: Sanctum ability checks + idle-timeout guard.
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'idle.timeout' => EnforceIdleTimeout::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Render all API exceptions through the standard error envelope.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            $details = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ((array) $messages as $message) {
                    $details[] = ['field' => $field, 'message' => $message];
                }
            }

            return ApiResponse::error('VALIDATION_ERROR', 'The request is invalid.', $details, 422);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('UNAUTHENTICATED', 'Authentication is required.', [], 401);
        });

        // Policy/Gate denials surface as AuthorizationException, which Laravel
        // converts to AccessDeniedHttpException before render callbacks run.
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('FORBIDDEN', 'You do not have permission to perform this action.', [], 403);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('TOO_MANY_REQUESTS', 'Too many attempts. Please try again later.', [], 429);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('NOT_FOUND', 'The requested resource was not found.', [], 404);
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('HTTP_ERROR', $e->getMessage() ?: 'Request could not be processed.', [], $e->getStatusCode());
        });
    })->create();
