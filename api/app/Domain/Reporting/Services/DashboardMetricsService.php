<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Computes the consolidated dashboard metrics for a {@see DashboardScope} (PRD
 * FR-RPT-01/02). This is the COMPUTE side — it reads raw tables and is run OFF the
 * request path (by the snapshot refresh); the request path reads the precomputed
 * snapshot instead. Every query bypasses the request-time MdaScope and applies the
 * scope EXPLICITLY, so scoping is identical in a request or on the scheduler/queue.
 *
 * All output is de-identified aggregate data (counts/sums by non-PII dimensions) —
 * never a beneficiary field (SECURITY.md §1). Benefit figures reuse the Phase 4
 * {@see LedgerAggregator}. Coordination metrics (duplicates/referrals/grievances)
 * are MDA/state concepts and are null for a partner scope.
 */
class DashboardMetricsService
{
    public function __construct(private readonly LedgerAggregator $ledger) {}

    /**
     * @return array<string, mixed>
     */
    public function compute(DashboardScope $scope): array
    {
        $coordination = $scope->includesCoordinationMetrics();

        return [
            'registry' => $this->registry($scope),
            'programmes' => $this->programmes($scope),
            'duplicates' => $coordination ? $this->duplicates($scope) : null,
            'benefits' => $this->benefits($scope),
            'referrals' => $coordination ? $this->referrals($scope) : null,
            'grievances' => $coordination ? $this->grievances($scope) : null,
            'coverage' => $this->coverage($scope),
        ];
    }

    /* ------------------------------------------------------------- registry (FR-REG) */

    /**
     * @return array<string, mixed>
     */
    private function registry(DashboardScope $scope): array
    {
        $beneficiaries = $this->beneficiaryBase($scope);
        $households = $this->householdBase($scope);

        return [
            'beneficiaries' => [
                'total' => (clone $beneficiaries)->count(),
                'by_status' => $this->countBy($beneficiaries, 'status'),
                'by_source' => $this->countBy($beneficiaries, 'registration_source'),
                'by_lga' => $this->countBy($beneficiaries, 'lga'),
            ],
            'households' => $households === null ? null : [
                'total' => (clone $households)->count(),
                'by_lga' => $this->countBy($households, 'lga'),
            ],
        ];
    }

    /* ------------------------------------------------------------ programmes (FR-PRG) */

    /**
     * Programme counts in scope (headline "active programmes", FR-RPT-01). Programmes
     * are a GLOBAL catalog (§10), so an MDA "runs" the distinct catalog programmes it
     * has activities for; a partner sees its funded set; state-wide is the whole catalog.
     *
     * @return array<string, int>
     */
    private function programmes(DashboardScope $scope): array
    {
        $ids = null; // state-wide: the whole catalog
        if ($scope->isPartner()) {
            $ids = $scope->programmeIds ?? [];
        } elseif ($scope->mdaIds !== null) {
            $ids = Activity::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('owner_mda_id', $scope->mdaIds)
                ->distinct()
                ->pluck('programme_id')
                ->all();
        }

        $query = Programme::query();
        if ($ids !== null) {
            $query->whereIn('id', $ids);
        }

        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
        ];
    }

    /* -------------------------------------------------- duplicate resolution (FR-DUP) */

    /**
     * Matches surfaced during import + how staged rows were resolved (new vs served
     * vs skipped). Scoped by the importing MDA (`import_batches.owner_mda_id`).
     *
     * @return array<string, mixed>
     */
    private function duplicates(DashboardScope $scope): array
    {
        $rows = DB::table('import_rows')
            ->join('import_batches', 'import_rows.import_batch_id', '=', 'import_batches.id')
            ->when($scope->mdaIds !== null, fn ($q) => $q->whereIn('import_batches.owner_mda_id', $scope->mdaIds));

        return [
            'matches_surfaced' => (clone $rows)->whereIn('import_rows.match_band', ['exact', 'probable'])->count(),
            'resolved_new' => (clone $rows)->where('import_rows.resolution', 'new')->count(),
            'resolved_served' => (clone $rows)->where('import_rows.resolution', 'link')->count(),
            'resolved_skipped' => (clone $rows)->where('import_rows.resolution', 'skip')->count(),
        ];
    }

    /* --------------------------------------------------------------- benefits (FR-BEN) */

    /**
     * @return array<string, mixed>
     */
    private function benefits(DashboardScope $scope): array
    {
        return [
            'disbursed' => $this->ledger->scopedTotals($scope->mdaIds, $scope->programmeIds),
            'budget' => $this->ledger->scopedBudget($scope->mdaIds, $scope->programmeIds),
            'by_type' => $this->ledger->scopedGroup('benefit_type', $scope->mdaIds, $scope->programmeIds),
        ];
    }

    /* -------------------------------------------------------------- referrals (FR-REF) */

    /**
     * @return array<string, mixed>
     */
    private function referrals(DashboardScope $scope): array
    {
        $base = Referral::query()->withoutGlobalScopes();
        if ($scope->mdaIds !== null) {
            $ids = $scope->mdaIds;
            $base->where(fn (Builder $w) => $w->whereIn('from_mda_id', $ids)->orWhereIn('to_mda_id', $ids));
        }

        $completed = (clone $base)->whereNotNull('completed_at')->get(['created_at', 'completed_at']);
        $total = (clone $base)->count();
        $completedCount = $completed->count();

        return [
            'total' => $total,
            'by_status' => $this->countBy($base, 'status'),
            'completed' => $completedCount,
            'completion_rate' => $total > 0 ? round($completedCount / $total, 4) : null,
            'overdue' => (clone $base)->whereNotNull('sla_breached_at')->count(),
            'avg_completion_days' => $completed->isEmpty()
                ? null
                : round($completed->avg(fn ($r) => $r->created_at->diffInDays($r->completed_at)), 1),
        ];
    }

    /* ------------------------------------------------------------- grievances (FR-GRM) */

    /**
     * @return array<string, mixed>
     */
    private function grievances(DashboardScope $scope): array
    {
        $base = Grievance::query()->withoutGlobalScope(MdaScope::class);
        if ($scope->mdaIds !== null) {
            $base->whereIn('handling_mda_id', $scope->mdaIds);
        }

        $resolved = (clone $base)->whereNotNull('resolved_at')->get(['created_at', 'resolved_at']);

        return [
            'total' => (clone $base)->count(),
            'by_status' => $this->countBy($base, 'status'),
            'sla_breaches' => (clone $base)->whereNotNull('sla_breached_at')->count(),
            'avg_resolution_days' => $resolved->isEmpty()
                ? null
                : round($resolved->avg(fn ($r) => $r->created_at->diffInDays($r->resolved_at)), 1),
        ];
    }

    /* --------------------------------------------------------- coverage by LGA (FR-GIS) */

    /**
     * Coverage by LGA — beneficiaries plus delivered benefits per area. Feeds the
     * registry map/table; the GIS step joins these keys to boundaries.
     *
     * @return array<int, array<string, mixed>>
     */
    private function coverage(DashboardScope $scope): array
    {
        $beneficiaryByLga = $this->countBy($this->beneficiaryBase($scope), 'lga');
        $benefitByLga = $this->ledger->scopedGroup('lga', $scope->mdaIds, $scope->programmeIds);

        $out = [];
        foreach ($beneficiaryByLga as $lga => $count) {
            $out[$lga] = ['lga' => $lga, 'beneficiary_count' => $count, 'benefit_count' => 0, 'benefit_value' => 0];
        }
        foreach ($benefitByLga as $group) {
            $lga = $group['key'] ?? 'unspecified';
            $out[$lga] ??= ['lga' => $lga, 'beneficiary_count' => 0, 'benefit_count' => 0, 'benefit_value' => 0];
            $out[$lga]['benefit_count'] = $group['benefit_count'];
            $out[$lga]['benefit_value'] = $group['total_value'];
        }

        return array_values($out);
    }

    /* -------------------------------------------------------------------- scope bases */

    /**
     * Beneficiaries in scope. For a partner this is the beneficiaries SERVED by their
     * funded programmes (via the ledger); otherwise the MDA-owned registry.
     *
     * @return Builder<Beneficiary>
     */
    private function beneficiaryBase(DashboardScope $scope): Builder
    {
        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);

        if ($scope->isPartner()) {
            $servedIds = Benefit::query()
                ->withoutGlobalScope(MdaScope::class)
                ->whereIn('programme_id', $scope->programmeIds ?? [])
                ->distinct()
                ->pluck('beneficiary_id')
                ->all();

            return $query->whereIn('id', $servedIds);
        }

        if ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }

        return $query;
    }

    /**
     * Households in scope, or null for a partner (households are an owner concept).
     *
     * @return Builder<Household>|null
     */
    private function householdBase(DashboardScope $scope): ?Builder
    {
        if ($scope->isPartner()) {
            return null;
        }

        $query = Household::query()->withoutGlobalScope(MdaScope::class);
        if ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }

        return $query;
    }

    /**
     * Grouped counts by a column (nulls bucketed as "unspecified"). Non-PII columns
     * only (status/source/lga/ward).
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     * @return array<string, int>
     */
    private function countBy(Builder $query, string $column): array
    {
        $rows = (clone $query)
            ->selectRaw("{$column} as k, count(*) as c")
            ->groupBy($column)
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $key = $row->getAttribute('k');
            $out[$key === null || $key === '' ? 'unspecified' : (string) $key] = (int) $row->getAttribute('c');
        }

        return $out;
    }
}
