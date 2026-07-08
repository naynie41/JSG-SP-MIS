<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;

/**
 * The read seam every dashboard uses (PRD FR-RPT-01/02, FR-DSH-01). Resolves the
 * caller's scope, then serves the precomputed snapshot for that scope — a single
 * indexed row, not a raw-table scan. On a cold miss (no snapshot yet) it warms the
 * scope once so the first caller still gets data; steady state is always cache/summary.
 */
class DashboardService
{
    public function __construct(
        private readonly DashboardScopeResolver $resolver,
        private readonly DashboardSnapshotService $snapshots,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user): array
    {
        $scope = $this->resolver->forUser($user);
        $snapshot = $this->snapshots->read($scope) ?? $this->snapshots->refreshFor($scope);

        return [
            'scope' => [
                'kind' => $scope->kind,
                'label' => $scope->label,
            ],
            'computed_at' => $snapshot->computed_at->toIso8601String(),
            'metrics' => $snapshot->metrics,
        ];
    }
}
