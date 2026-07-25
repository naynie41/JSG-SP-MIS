<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Reporting\Support\DashboardScope;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Models\SyncRun;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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
        $coverage = $this->coverage($scope);

        return [
            'registry' => $this->registry($scope),
            'programmes' => $this->programmes($scope),
            'duplicates' => $coordination ? $this->duplicates($scope) : null,
            'benefits' => $this->benefits($scope),
            'referrals' => $coordination ? $this->referrals($scope) : null,
            'grievances' => $coordination ? $this->grievances($scope) : null,
            'coverage' => $coverage,

            // ---- Phase 6E executive metrics (all scoped + de-identified aggregates) ----
            'population' => $this->population($scope),
            'demographics' => $this->demographics($scope),
            'household_size' => $this->householdSize($scope),
            'programme_performance' => $this->programmePerformance($scope),
            'programme_scoring' => [
                'green_min' => (float) config('reporting.programme_traffic_light.green_min', 0.8),
                'yellow_min' => (float) config('reporting.programme_traffic_light.yellow_min', 0.5),
            ],
            'registry_quality' => $this->registryQuality($scope),
            'coordination' => $coordination ? $this->coordination($scope) : null,
            'coverage_bands' => $this->coverageBands($coverage),
            'trends' => $this->trends($scope),
            // Deferred slots exist (stable shape) but return nothing until switched on.
            'deferred' => $this->deferredSlots(),
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

        // Activities scoped the same way (partner → funded programmes; MDA → owned; else state-wide).
        $activities = Activity::query()->withoutGlobalScope(MdaScope::class);
        if ($scope->isPartner()) {
            $activities->whereIn('programme_id', $scope->programmeIds ?? []);
        } elseif ($scope->mdaIds !== null) {
            $activities->whereIn('owner_mda_id', $scope->mdaIds);
        }

        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'activities_total' => (clone $activities)->count(),
            'activities_active' => (clone $activities)->where('status', ActivityStatus::Active->value)->count(),
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

    /* ============================ Phase 6E executive metrics ============================ */

    /**
     * Population headline — NET-UNIQUE only. `total_individuals` is the deduplicated
     * registry count in scope; `net_unique_served` is distinct beneficiaries with a
     * delivery (never the gross delivery count, which is `benefits.disbursed.benefit_count`).
     *
     * @return array<string, mixed>
     */
    private function population(DashboardScope $scope): array
    {
        $base = $this->beneficiaryBase($scope);
        $households = $this->householdBase($scope);
        $days = (int) config('reporting.current_period_days', 30);
        $since = Carbon::now()->subDays($days)->toDateString();

        return [
            'total_households' => $households === null ? 0 : (clone $households)->count(),
            'total_individuals' => (clone $base)->count(),
            'net_unique_served' => $this->ledger->scopedDistinctBeneficiaries($scope->mdaIds, $scope->programmeIds),
            'new_registrations_period' => (clone $base)->whereDate('registration_date', '>=', $since)->count(),
            'lgas_covered' => (clone $base)->whereNotNull('lga')->distinct()->count('lga'),
            'wards_covered' => (clone $base)->whereNotNull('ward')->distinct()->count('ward'),
            'period_days' => $days,
        ];
    }

    /**
     * Demographics from existing fields only (gender, DOB → age bands, household vs
     * individual). Percentages are over KNOWN values; "unspecified"/"unknown" is kept
     * explicit so partial coverage is visible.
     *
     * @return array<string, mixed>
     */
    private function demographics(DashboardScope $scope): array
    {
        $base = $this->beneficiaryBase($scope);
        $total = (clone $base)->count();

        $byGender = $this->countBy($base, 'gender');
        $knownGender = $total - ($byGender['unspecified'] ?? 0);
        $female = $byGender['female'] ?? 0;

        $inHousehold = (clone $base)
            ->whereIn('id', HouseholdMembership::query()->whereNull('left_at')->select('beneficiary_id'))
            ->count();

        return [
            'total' => $total,
            'by_gender' => $byGender,
            'gender_known' => $knownGender,
            'female_pct' => $knownGender > 0 ? round($female / $knownGender, 4) : null,
            'age_bands' => $this->ageBands($base),
            'household_vs_individual' => [
                'in_household' => $inHousehold,
                'individual' => max(0, $total - $inHousehold),
            ],
        ];
    }

    /**
     * Age-band counts from date_of_birth using config cut points. Boundaries are
     * computed as DATES (today − age) so the comparison is driver-portable and the
     * band edges are exact; records without a DOB fall into "unknown".
     *
     * @param  Builder<Beneficiary>  $base
     * @return array<string, int>
     */
    private function ageBands(Builder $base): array
    {
        $today = Carbon::today();
        $out = [];
        foreach ((array) config('reporting.age_bands', []) as $label => $range) {
            [$min, $max] = $range;
            $q = (clone $base)
                ->whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '<=', $today->copy()->subYears((int) $min)->toDateString());
            if ($max !== null) {
                $q->whereDate('date_of_birth', '>', $today->copy()->subYears((int) $max)->toDateString());
            }
            $out[(string) $label] = $q->count();
        }
        $out['unknown'] = (clone $base)->whereNull('date_of_birth')->count();

        return $out;
    }

    /**
     * Household-size distribution over the scoped households — a field we HAVE (active
     * memberships per household), banded into 1 / 2–3 / 4–6 / 7+. Partners own no
     * households, so this is empty for them. NOTE: poverty/disability/PWD/IDP/occupation
     * breakdowns are intentionally absent — those fields are not captured (deferred slot).
     *
     * @return array<string, mixed>
     */
    private function householdSize(DashboardScope $scope): array
    {
        $bands = ['1' => 0, '2-3' => 0, '4-6' => 0, '7+' => 0];
        $households = $this->householdBase($scope);
        if ($households === null) {
            return ['total_households' => 0, 'households_with_members' => 0, 'average_size' => null, 'bands' => $bands];
        }

        $total = (clone $households)->count();
        $sizes = HouseholdMembership::query()
            ->whereNull('left_at')
            ->whereIn('household_id', (clone $households)->select('id'))
            ->selectRaw('household_id, count(*) as member_count')
            ->groupBy('household_id')
            ->pluck('member_count');

        $members = 0;
        foreach ($sizes as $size) {
            $s = (int) $size;
            $members += $s;
            $key = $s <= 1 ? '1' : ($s <= 3 ? '2-3' : ($s <= 6 ? '4-6' : '7+'));
            $bands[$key]++;
        }
        $withMembers = $sizes->count();

        return [
            'total_households' => $total,
            'households_with_members' => $withMembers,
            'average_size' => $withMembers > 0 ? round($members / $withMembers, 2) : null,
            'bands' => $bands,
        ];
    }

    /**
     * Per-programme performance: status, implementing MDA(s), start/end (derived from
     * the scoped activities), target vs net-unique reached, completion, coverage
     * (absolute), budget allocated/spent/remaining, cost per beneficiary, a
     * configurable traffic-light score, and the activity-level drill-down. Everything
     * is scoped: a partner sees only its funded programmes' activities, an MDA only its
     * own — so the drill-down never exposes activities outside the caller's remit.
     *
     * @return list<array<string, mixed>>
     */
    private function programmePerformance(DashboardScope $scope): array
    {
        $programmeIds = $this->programmeIdsInScope($scope);
        if ($programmeIds === []) {
            return [];
        }

        $activityQuery = Activity::query()->withoutGlobalScope(MdaScope::class)->whereIn('programme_id', $programmeIds);
        if ($scope->mdaIds !== null && ! $scope->isPartner()) {
            $activityQuery->whereIn('owner_mda_id', $scope->mdaIds);
        }
        $activityAgg = (clone $activityQuery)
            ->selectRaw('programme_id, coalesce(sum(target_beneficiaries), 0) as target, coalesce(sum(budget_amount), 0) as allocated')
            ->groupBy('programme_id')
            ->get()
            ->keyBy('programme_id');

        // Scoped activity rows (metadata + drill-down), grouped by programme (one query).
        $activityRows = (clone $activityQuery)
            ->get(['id', 'programme_id', 'owner_mda_id', 'name', 'status', 'budget_amount', 'target_beneficiaries', 'starts_on', 'ends_on'])
            ->groupBy('programme_id');
        $mdaNames = Mda::query()
            ->whereIn('id', $activityRows->flatten(1)->pluck('owner_mda_id')->filter()->unique()->all())
            ->pluck('name', 'id');

        $reach = $this->ledger->scopedReachByProgramme($scope->mdaIds, $programmeIds);
        $activityReach = $this->ledger->scopedReachByActivity($scope->mdaIds, $programmeIds);
        $spent = collect($this->ledger->scopedGroup('programme', $scope->mdaIds, $programmeIds))
            ->keyBy('key')
            ->map(fn (array $g) => (int) $g['total_value']);
        $activitySpent = collect($this->ledger->scopedGroup('activity', $scope->mdaIds, $programmeIds))
            ->keyBy('key')
            ->map(fn (array $g) => (int) $g['total_value']);
        $programmes = Programme::query()->whereIn('id', $programmeIds)->get(['id', 'name', 'status'])->keyBy('id');

        $green = (float) config('reporting.programme_traffic_light.green_min', 0.8);
        $yellow = (float) config('reporting.programme_traffic_light.yellow_min', 0.5);
        $light = fn (?float $c): string => $c === null
            ? 'unrated'
            : ($c >= $green ? 'green' : ($c >= $yellow ? 'yellow' : 'red'));
        $day = fn ($d): ?string => $d instanceof \DateTimeInterface ? $d->format('Y-m-d') : ($d === null ? null : (string) $d);

        $out = [];
        foreach ($programmeIds as $id) {
            $target = (int) ($activityAgg[$id]->target ?? 0);
            $allocated = (int) ($activityAgg[$id]->allocated ?? 0);
            $reached = (int) ($reach[$id] ?? 0);
            $spentValue = (int) ($spent[$id] ?? 0);
            $completion = $target > 0 ? round($reached / $target, 4) : null;

            $rows = $activityRows[$id] ?? collect();
            $starts = $rows->pluck('starts_on')->filter()->map($day)->all();
            $ends = $rows->pluck('ends_on')->filter()->map($day)->all();
            $mdaIds = $rows->pluck('owner_mda_id')->filter()->unique()->values();

            $activities = $rows->map(function (Activity $a) use ($activityReach, $activitySpent, $mdaNames, $light) {
                $aReached = (int) ($activityReach[$a->id] ?? 0);
                $aTarget = (int) ($a->target_beneficiaries ?? 0);
                $aAllocated = (int) ($a->budget_amount ?? 0);
                $aSpent = (int) ($activitySpent[$a->id] ?? 0);
                $aCompletion = $aTarget > 0 ? round($aReached / $aTarget, 4) : null;

                return [
                    'activity_id' => $a->id,
                    'name' => $a->name,
                    'mda' => $a->owner_mda_id !== null ? ($mdaNames[$a->owner_mda_id] ?? null) : null,
                    'status' => $a->status instanceof \BackedEnum ? $a->status->value : $a->status,
                    'target' => $aTarget,
                    'reached' => $aReached,
                    'completion_rate' => $aCompletion,
                    'coverage_absolute' => $aReached,
                    'budget' => ['allocated' => $aAllocated, 'spent' => $aSpent, 'remaining' => $aAllocated - $aSpent],
                    'cost_per_beneficiary' => $aReached > 0 ? (int) round($aSpent / $aReached) : null,
                    'traffic_light' => $light($aCompletion),
                ];
            })->values()->all();

            $programme = $programmes[$id] ?? null;

            $out[] = [
                'programme_id' => $id,
                'name' => $programme?->name,
                'status' => $programme?->status instanceof \BackedEnum ? $programme->status->value : $programme?->status,
                'mdas' => $mdaIds->map(fn ($mid) => ['id' => $mid, 'name' => $mdaNames[$mid] ?? null])->all(),
                'start_date' => $starts === [] ? null : min($starts),
                'end_date' => $ends === [] ? null : max($ends),
                'target' => $target,
                'reached' => $reached,
                'completion_rate' => $completion,
                'coverage_absolute' => $reached,
                'budget' => [
                    'allocated' => $allocated,
                    'spent' => $spentValue,
                    'remaining' => $allocated - $spentValue,
                    'utilization_rate' => $allocated > 0 ? round($spentValue / $allocated, 4) : null,
                ],
                'cost_per_beneficiary' => $reached > 0 ? (int) round($spentValue / $reached) : null,
                'traffic_light' => $light($completion),
                'activities' => $activities,
            ];
        }

        return $out;
    }

    /**
     * Registry quality/integrity. "verified/pending" map to the review status we hold
     * (active = clean, flagged = pending review); completeness is field presence
     * (NIN via the keyed hash, phone, DOB, gender, LGA). No PII values — presence only.
     *
     * @return array<string, mixed>
     */
    private function registryQuality(DashboardScope $scope): array
    {
        $base = $this->beneficiaryBase($scope);
        $total = (clone $base)->count();
        $byStatus = $this->countBy($base, 'status');

        $withNin = (clone $base)->whereNotNull('nin_hash')->count();
        $withPhone = (clone $base)->whereNotNull('phone')->count();
        $withDob = (clone $base)->whereNotNull('date_of_birth')->count();
        $withGender = (clone $base)->whereNotNull('gender')->count();
        $withLga = (clone $base)->whereNotNull('lga')->count();
        $withId = (clone $base)->where(fn (Builder $w) => $w->whereNotNull('nin_hash')->orWhereNotNull('bvn_hash'))->count();

        $ratio = fn (int $n): ?float => $total > 0 ? round($n / $total, 4) : null;

        return [
            'total' => $total,
            'verified' => $byStatus['active'] ?? 0,
            'pending' => $byStatus['flagged'] ?? 0,
            'suspended' => $byStatus['suspended'] ?? 0,
            'duplicates_detected' => $scope->isPartner() ? 0 : $this->matchesSurfaced($scope),
            'nin_completeness' => $ratio($withNin),
            'phone_completeness' => $ratio($withPhone),
            'data_completeness' => $total > 0
                ? round(($withId + $withPhone + $withDob + $withGender + $withLga) / (5 * $total), 4)
                : null,
        ];
    }

    private function matchesSurfaced(DashboardScope $scope): int
    {
        return (int) DB::table('import_rows')
            ->join('import_batches', 'import_rows.import_batch_id', '=', 'import_batches.id')
            ->when($scope->mdaIds !== null, fn ($q) => $q->whereIn('import_batches.owner_mda_id', $scope->mdaIds))
            ->whereIn('import_rows.match_band', ['exact', 'probable'])
            ->count();
    }

    /**
     * Coordination across agencies: active delivering MDAs, cross-MDA (joint, served
     * by a non-owner) beneficiaries — net-unique, referral throughput, request-to-serve,
     * partner contributions, and sync/API health.
     *
     * @return array<string, mixed>
     */
    private function coordination(DashboardScope $scope): array
    {
        $activeMdas = (int) Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->when($scope->mdaIds !== null, fn ($q) => $q->whereIn('mda_id', $scope->mdaIds))
            ->distinct()->count('mda_id');

        $joint = (int) Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->join('beneficiaries', 'benefits.beneficiary_id', '=', 'beneficiaries.id')
            ->whereColumn('benefits.mda_id', '!=', 'beneficiaries.owner_mda_id')
            ->when($scope->mdaIds !== null, fn ($q) => $q->whereIn('benefits.mda_id', $scope->mdaIds))
            ->distinct()->count('benefits.beneficiary_id');

        $refBase = Referral::query()->withoutGlobalScopes();
        if ($scope->mdaIds !== null) {
            $ids = $scope->mdaIds;
            $refBase->where(fn (Builder $w) => $w->whereIn('from_mda_id', $ids)->orWhereIn('to_mda_id', $ids));
        }
        $refTotal = (clone $refBase)->count();
        $refCompleted = (clone $refBase)->whereNotNull('completed_at')->count();

        $srBase = ServiceRequest::query();
        if ($scope->mdaIds !== null) {
            $ids = $scope->mdaIds;
            $srBase->where(fn (Builder $w) => $w->whereIn('from_mda_id', $ids)->orWhereIn('to_mda_id', $ids));
        }
        $srByStatus = $this->countBy($srBase, 'status');
        $accepted = $srByStatus['accepted'] ?? 0;
        $declined = $srByStatus['declined'] ?? 0;
        $decided = $accepted + $declined;

        // Turnaround: mean hours from raised (created_at) to decided (decided_at).
        $decidedRows = (clone $srBase)->whereNotNull('decided_at')->get(['created_at', 'decided_at']);
        $avgTurnaround = $decidedRows->isEmpty() ? null : round((float) $decidedRows->avg(
            fn (ServiceRequest $r) => max(0, ($r->decided_at->getTimestamp() - $r->created_at->getTimestamp()) / 3600)
        ), 1);

        $syncBase = SyncRun::query()->withoutGlobalScopes();
        $connectorBase = SyncConnector::query()->withoutGlobalScopes();
        if ($scope->mdaIds !== null) {
            $syncBase->whereIn('owner_mda_id', $scope->mdaIds);
            $connectorBase->whereIn('owner_mda_id', $scope->mdaIds);
        }
        $lastRun = (clone $syncBase)->latest('created_at')->first();

        return [
            'active_mdas' => $activeMdas,
            'joint_programmes' => $this->jointProgrammes($scope),
            'cross_mda_beneficiaries' => $joint,
            'referral_throughput' => [
                'total' => $refTotal,
                'completed' => $refCompleted,
                'completion_rate' => $refTotal > 0 ? round($refCompleted / $refTotal, 4) : null,
            ],
            'request_to_serve' => [
                'raised' => array_sum($srByStatus),
                'accepted' => $accepted,
                'declined' => $declined,
                'pending' => $srByStatus['pending'] ?? 0,
                'approval_rate' => $decided > 0 ? round($accepted / $decided, 4) : null,
                'avg_turnaround_hours' => $avgTurnaround,
            ],
            'partners' => $this->partnerContributions($scope),
            'sync_health' => [
                'total_runs' => (clone $syncBase)->count(),
                'succeeded' => (clone $syncBase)->where('status', 'completed')->count(),
                'failed' => (clone $syncBase)->where('status', 'failed')->count(),
                'last_run_at' => $lastRun?->created_at?->toIso8601String(),
                'api_registrations' => (clone $this->beneficiaryBase($scope))->where('registration_source', 'api')->count(),
                'connectors' => (clone $connectorBase)->count(),
                'sources' => (clone $connectorBase)->distinct()->pluck('source')->filter()->values()->all(),
            ],
        ];
    }

    /**
     * Programmes run jointly by two or more MDAs (activities owned by ≥2 MDAs), within
     * scope. State-wide covers the whole catalog; an MDA sees the joint programmes it
     * participates in.
     */
    private function jointProgrammes(DashboardScope $scope): int
    {
        $scopeProgrammeIds = $scope->isStateWide() ? null : $this->programmeIdsInScope($scope);
        if ($scopeProgrammeIds === []) {
            return 0;
        }

        $query = Activity::query()->withoutGlobalScope(MdaScope::class);
        if ($scopeProgrammeIds !== null) {
            $query->whereIn('programme_id', $scopeProgrammeIds);
        }

        return $query->select('programme_id')
            ->groupBy('programme_id')
            ->havingRaw('count(distinct owner_mda_id) > 1')
            ->get()
            ->count();
    }

    /**
     * Partner (funder) contributions: distinct partners, funded programmes, net-unique
     * beneficiaries served through them, allocated funding, and a PER-PARTNER breakdown.
     * Every figure is scoped to funded programmes in view (a partner/MDA never sees
     * funding beyond its own remit); state-wide covers all funders.
     *
     * @return array<string, mixed>
     */
    private function partnerContributions(DashboardScope $scope): array
    {
        $empty = ['count' => 0, 'funded_programmes' => 0, 'beneficiaries_served' => 0, 'funding_allocated' => 0, 'list' => []];

        $funders = ProgrammeFunder::query();
        if (! $scope->isStateWide()) {
            $scopeProgrammes = $this->programmeIdsInScope($scope);
            if ($scopeProgrammes === []) {
                return $empty;
            }
            $funders->whereIn('programme_id', $scopeProgrammes);
        }

        $rows = $funders->get(['user_id', 'programme_id']);
        $programmeIds = $rows->pluck('programme_id')->unique()->values()->all();
        if ($programmeIds === []) {
            return $empty;
        }

        $names = User::query()->whereIn('id', $rows->pluck('user_id')->unique()->all())->pluck('name', 'id');
        $fundingFor = fn (array $ids): int => (int) Activity::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('programme_id', $ids)->sum('budget_amount');

        $list = $rows->groupBy('user_id')->map(function ($partnerRows, $userId) use ($names, $fundingFor) {
            $pids = $partnerRows->pluck('programme_id')->unique()->values()->all();

            return [
                'partner_id' => (string) $userId,
                'name' => $names[$userId] ?? 'Partner',
                'funded_programmes' => count($pids),
                'beneficiaries_served' => $this->ledger->scopedDistinctBeneficiaries(null, $pids),
                'funding_allocated' => $fundingFor($pids),
            ];
        })->sortByDesc('funding_allocated')->values()->all();

        return [
            'count' => $rows->pluck('user_id')->unique()->count(),
            'funded_programmes' => count($programmeIds),
            'beneficiaries_served' => $this->ledger->scopedDistinctBeneficiaries(null, $programmeIds),
            'funding_allocated' => $fundingFor($programmeIds),
            'list' => $list,
        ];
    }

    /**
     * Coverage banding by ABSOLUTE beneficiaries per area against configurable
     * thresholds (green/yellow/red) — explicitly NOT a % of population.
     *
     * @param  array<int, array<string, mixed>>  $coverageRows
     * @return array<string, mixed>
     */
    private function coverageBands(array $coverageRows): array
    {
        $green = (int) config('reporting.coverage_bands.green_min', 1000);
        $yellow = (int) config('reporting.coverage_bands.yellow_min', 250);

        $summary = ['green' => 0, 'yellow' => 0, 'red' => 0];
        $areas = [];
        foreach ($coverageRows as $row) {
            $count = (int) ($row['beneficiary_count'] ?? 0);
            $band = $count >= $green ? 'green' : ($count >= $yellow ? 'yellow' : 'red');
            $summary[$band]++;
            $areas[] = ['lga' => $row['lga'], 'beneficiary_count' => $count, 'band' => $band];
        }

        return [
            'basis' => 'absolute',
            'thresholds' => ['green_min' => $green, 'yellow_min' => $yellow],
            'summary' => $summary,
            'areas' => $areas,
        ];
    }

    /**
     * Periodised trends over the last N months: net-new registrations, cumulative
     * beneficiaries (from a pre-window baseline), disbursement value, and programme
     * growth. Months are zero-filled so the series is a complete, ordered timeline.
     *
     * @return array<string, mixed>
     */
    private function trends(DashboardScope $scope): array
    {
        $months = (int) config('reporting.trend_months', 12);
        $labels = $this->monthLabels($months);

        $registrations = $this->monthCountSeries($this->beneficiaryBase($scope), 'registration_date', $months);
        $disbursement = $this->ledger->scopedDisbursementSeries($scope->mdaIds, $scope->programmeIds, $months);
        $programmes = $this->programmeGrowthSeries($scope, $months);

        $firstStart = Carbon::createFromFormat('Y-m', $labels[0])->startOfMonth()->toDateString();
        $running = (clone $this->beneficiaryBase($scope))->whereDate('registration_date', '<', $firstStart)->count();

        $cumulative = [];
        foreach ($labels as $m) {
            $running += $registrations[$m] ?? 0;
            $cumulative[] = ['month' => $m, 'value' => $running];
        }

        $series = fn (array $data) => array_map(fn (string $m) => ['month' => $m, 'value' => $data[$m] ?? 0], $labels);

        return [
            'months' => $labels,
            'registrations' => $series($registrations),
            'beneficiaries_cumulative' => $cumulative,
            'disbursement' => $series($disbursement),
            'programme_growth' => $series($programmes),
        ];
    }

    /**
     * Deferred aggregation slots (NFR — field/denominator-dependent). They exist with
     * a stable key so they can switch on later, but return null today and must not be
     * rendered. Not exposed as a live metric.
     *
     * @return array<string, null>
     */
    private function deferredSlots(): array
    {
        return [
            'population_penetration' => null,   // needs an LGA/ward population baseline
            'targeting_accuracy' => null,       // needs a poverty register / PMT denominator
            'vulnerability_categories' => null, // no vulnerability/disability field captured
            'outcome_indicators' => null,       // needs M&E survey/outcome integration
            'identity_verification' => null,    // needs an explicit identity-verification field
        ];
    }

    /* ---------------------------------------------------------------- trend helpers */

    /**
     * @return list<string> the last N 'YYYY-MM' month labels, oldest first
     */
    private function monthLabels(int $months): array
    {
        $labels = [];
        $cursor = Carbon::now()->startOfMonth()->subMonths(max(0, $months - 1));
        for ($i = 0; $i < max(1, $months); $i++) {
            $labels[] = $cursor->format('Y-m');
            $cursor->addMonth();
        }

        return $labels;
    }

    /**
     * Monthly row counts for a date column over the last N months.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $base
     * @return array<string, int>
     */
    private function monthCountSeries(Builder $base, string $dateColumn, int $months): array
    {
        $expr = LedgerAggregator::monthKeyExpr($dateColumn);
        $since = Carbon::now()->startOfMonth()->subMonths(max(0, $months - 1))->toDateString();

        return (clone $base)
            ->whereDate($dateColumn, '>=', $since)
            ->selectRaw("{$expr} as m, count(*) as c")
            ->groupByRaw($expr)
            ->get()
            ->mapWithKeys(fn ($r) => [(string) $r->getAttribute('m') => (int) $r->getAttribute('c')])
            ->all();
    }

    /**
     * Programmes created per month over the last N months, within scope.
     *
     * @return array<string, int>
     */
    private function programmeGrowthSeries(DashboardScope $scope, int $months): array
    {
        $ids = $this->programmeIdsInScope($scope);
        if ($ids === []) {
            return [];
        }
        $expr = LedgerAggregator::monthKeyExpr('created_at');
        $since = Carbon::now()->startOfMonth()->subMonths(max(0, $months - 1))->toDateString();

        return Programme::query()
            ->whereIn('id', $ids)
            ->whereDate('created_at', '>=', $since)
            ->selectRaw("{$expr} as m, count(*) as c")
            ->groupByRaw($expr)
            ->get()
            ->mapWithKeys(fn ($r) => [(string) $r->getAttribute('m') => (int) $r->getAttribute('c')])
            ->all();
    }

    /**
     * The catalog programme ids in scope: partner = funded set; MDA = programmes it
     * runs (has activities for); state-wide = the whole catalog.
     *
     * @return list<string>
     */
    private function programmeIdsInScope(DashboardScope $scope): array
    {
        if ($scope->isPartner()) {
            return $scope->programmeIds ?? [];
        }
        if ($scope->mdaIds !== null) {
            return Activity::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('owner_mda_id', $scope->mdaIds)
                ->distinct()->pluck('programme_id')->all();
        }

        return Programme::query()->pluck('id')->all();
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
