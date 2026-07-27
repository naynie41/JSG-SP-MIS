<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Support\DashboardFilter;
use Illuminate\Support\Carbon;

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
     * @return array<string, mixed>
     */
    public function forUser(User $user, ?DashboardFilter $filter = null): array
    {
        $scope = $this->resolver->forUser($user);
        $filter ??= DashboardFilter::none();

        if ($filter->isEmpty()) {
            $snapshot = $this->snapshots->read($scope) ?? $this->snapshots->refreshFor($scope);
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
            'metrics' => $metrics,
        ];
    }
}
