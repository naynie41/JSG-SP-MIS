<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Models\DashboardSnapshot;
use App\Domain\Reporting\Support\DashboardFilter;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * The read seam every dashboard uses (PRD FR-RPT-01/02, FR-DSH-01). Resolves the
 * caller's scope, then:
 *
 *  - UNFILTERED → serves the precomputed snapshot for that scope (a single indexed
 *    row, not a raw scan; cold miss warms it once);
 *  - FILTERED → recomputes the metrics live with the filter pushed into every query.
 *
 * The response always carries the caller's oversight TIER + the in-scope filter option
 * universe, so the UI only ever offers filters the caller may actually see.
 */
class DashboardService
{
    public function __construct(
        private readonly DashboardScopeResolver $resolver,
        private readonly DashboardSnapshotService $snapshots,
        private readonly DashboardMetricsService $metrics,
    ) {}

    /**
     * Recompute a scope's snapshot, but only once across concurrent readers.
     *
     * A missing or expired snapshot means every request for that scope arrives at the
     * same moment wanting the same expensive aggregate. The lock lets one of them do the
     * work; the rest wait briefly and then read what it wrote. Without it, a scheduler
     * outage on a busy MDA turns into a stampede over the raw registry.
     *
     * If the lock cannot be taken in time the caller computes anyway rather than
     * failing — a slow dashboard beats no dashboard, and the write is an idempotent
     * upsert keyed on the scope.
     */
    private function refreshOnce(DashboardScope $scope): DashboardSnapshot
    {
        $lock = Cache::lock('dashboard-snapshot:'.$scope->key(), 30);

        try {
            $lock->block(5);
        } catch (LockTimeoutException) {
            return $this->snapshots->readAtAnyAge($scope) ?? $this->snapshots->refreshFor($scope);
        }

        try {
            // Another reader may have refreshed it while we waited.
            return $this->snapshots->read($scope) ?? $this->snapshots->refreshFor($scope);
        } finally {
            $lock->release();
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user, ?DashboardFilter $filter = null): array
    {
        $scope = $this->resolver->forUser($user);
        $filter ??= DashboardFilter::none();

        if ($filter->isEmpty()) {
            $snapshot = $this->snapshots->read($scope) ?? $this->refreshOnce($scope);
            $metrics = $snapshot->metrics;
            $computedAt = $snapshot->computed_at->toIso8601String();
        } else {
            $metrics = $this->metrics->compute($scope, $filter);
            $computedAt = Carbon::now()->toIso8601String();
        }

        return [
            'scope' => [
                'kind' => $scope->kind,
                'label' => $scope->label,
                'tier' => $scope->tier(),
            ],
            'live' => ! $filter->isEmpty(),
            'filters' => $filter->toArray(),
            'filter_options' => $this->metrics->filterOptions($scope),
            'computed_at' => $computedAt,
            /*
             * The small-group threshold, published so the client does not carry its own
             * copy of a number the DPO owns (config `reporting.min_cell_size`). It is
             * the same value the segment builder suppresses on, and it applies here for
             * the same reason: on an AGGREGATE tier a headline of 2 identifies people.
             * `null` on the operational tier — an MDA already holds the records its own
             * dashboard counts, so there is nothing to re-identify.
             */
            'min_cell_size' => $scope->tier() === 'operational'
                ? null
                : max(0, (int) config('reporting.min_cell_size', 5)),
            'metrics' => $metrics,
        ];
    }
}
