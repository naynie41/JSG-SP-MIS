<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Reporting\Models\DashboardSnapshot;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Builds and stores the dashboard summary snapshots (PRD FR-RPT-01/02). Runs OFF the
 * request path (scheduler/queue): it computes each scope's metrics from raw tables
 * and upserts one `dashboard_snapshots` row per scope. The request path then reads a
 * single indexed row — never the raw ledger/registry.
 */
class DashboardSnapshotService
{
    public function __construct(private readonly DashboardMetricsService $metrics) {}

    /**
     * Refresh every well-known scope: state-wide, one per MDA, one per partner's
     * funded-programme set. De-duplicated by scope key. Returns the number written.
     */
    public function refreshAll(): int
    {
        $scopes = [DashboardScope::stateWide()];

        foreach (Mda::query()->get() as $mda) {
            $scopes[] = DashboardScope::mda([$mda->id], $mda->name);
        }

        foreach ($this->partnerUsers() as $partner) {
            $scopes[] = DashboardScope::partner(ProgrammeFunder::programmeIdsForUser($partner->id), 'Funded programmes');
        }

        $seen = [];
        foreach ($scopes as $scope) {
            if (isset($seen[$scope->key()])) {
                continue;
            }
            $seen[$scope->key()] = true;
            $this->refreshFor($scope);
        }

        return count($seen);
    }

    /** Compute + upsert the snapshot for a single scope. */
    public function refreshFor(DashboardScope $scope): DashboardSnapshot
    {
        return DashboardSnapshot::query()->updateOrCreate(
            ['scope_key' => $scope->key()],
            [
                'scope_kind' => $scope->kind,
                'scope_label' => $scope->label,
                'metrics' => $this->metrics->compute($scope),
                'computed_at' => Carbon::now(),
            ],
        );
    }

    public function read(DashboardScope $scope): ?DashboardSnapshot
    {
        return DashboardSnapshot::query()->where('scope_key', $scope->key())->first();
    }

    /**
     * @return Collection<int, User>
     */
    private function partnerUsers(): Collection
    {
        return User::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereHas('role', fn ($q) => $q->where('key', RoleKey::DevelopmentPartner->value))
            ->get();
    }
}
