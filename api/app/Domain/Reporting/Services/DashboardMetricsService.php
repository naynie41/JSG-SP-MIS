<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Reporting\Support\DashboardFilter;
use App\Domain\Reporting\Support\DashboardScope;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Models\SyncRun;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
    /**
     * The active cross-cutting filter for this compute pass. Set at the top of
     * {@see compute()} and read by the base query builders + ledger calls; it only ever
     * NARROWS the scope (applied on top of it), so it can never widen visibility.
     */
    private DashboardFilter $filter;

    public function __construct(private readonly LedgerAggregator $ledger)
    {
        $this->filter = DashboardFilter::none();
    }

    /**
     * Compute the metric bundle for a scope. When `$filter` is set (a filtered request),
     * every metric is recomputed live with the filter pushed into its queries; the
     * unfiltered snapshot path passes no filter.
     *
     * @return array<string, mixed>
     */
    public function compute(DashboardScope $scope, ?DashboardFilter $filter = null): array
    {
        $this->filter = $filter ?? DashboardFilter::none();
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
            // Phase 6P — activity-precise partner-funding aggregates (partner scope only).
            'partner_funding' => $scope->isPartner() ? $this->partnerFunding($scope) : null,
            'coverage_bands' => $this->coverageBands($coverage),
            'trends' => $this->trends($scope),
            // Deferred slots exist (stable shape) but return nothing until switched on.
            'deferred' => $this->deferredSlots(),
        ];
    }

    /**
     * The universe of filter options WITHIN a scope (unfiltered) — so the UI only ever
     * offers programmes/MDAs/areas the caller may actually see. Computed live (small
     * distinct lists); never widens the scope.
     *
     * @return array<string, mixed>
     */
    public function filterOptions(DashboardScope $scope): array
    {
        $this->filter = DashboardFilter::none();
        $base = $this->beneficiaryBase($scope);

        $programmeIds = $this->programmeIdsInScope($scope);
        $programmes = Programme::query()->whereIn('id', $programmeIds)
            ->orderBy('name')->get(['id', 'name'])
            ->map(fn (Programme $p) => ['id' => $p->id, 'name' => $p->name])->all();

        $mdaQuery = Mda::query();
        if ($scope->isPartner()) {
            $mdaIds = Activity::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('programme_id', $programmeIds)->distinct()->pluck('owner_mda_id')->all();
            $mdaQuery->whereIn('id', $mdaIds);
        } elseif ($scope->mdaIds !== null) {
            $mdaQuery->whereIn('id', $scope->mdaIds);
        }
        $mdas = $mdaQuery->orderBy('name')->get(['id', 'name'])
            ->map(fn (Mda $m) => ['id' => $m->id, 'name' => $m->name])->all();

        $lgas = (clone $base)->whereNotNull('lga')->distinct()->orderBy('lga')->pluck('lga')->values()->all();
        $wards = (clone $base)->whereNotNull('ward')->distinct()->orderBy('ward')->pluck('ward')->values()->all();

        $expr = LedgerAggregator::monthKeyExpr('registration_date');
        $years = (clone $base)->whereNotNull('registration_date')
            ->selectRaw("substr({$expr}, 1, 4) as y")->distinct()->orderByDesc('y')->pluck('y')
            ->map(fn ($y) => (int) $y)->values()->all();

        return [
            'programmes' => $programmes,
            'mdas' => $mdas,
            'lgas' => $lgas,
            'wards' => $wards,
            'years' => $years,
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
        // Activities scoped the same way (partner → funded; MDA → owned; else state-wide),
        // then narrowed by the cross-cutting filter (programme/MDA/area).
        $activities = Activity::query()->withoutGlobalScope(MdaScope::class);
        if ($scope->isPartner()) {
            $activities->whereIn('programme_id', $scope->programmeIds ?? []);
        } elseif ($scope->mdaIds !== null) {
            $activities->whereIn('owner_mda_id', $scope->mdaIds);
        }
        $this->applyActivityFilter($activities);

        // Catalog membership: whole catalog only for an unfiltered state-wide view; a
        // partner sees its funded set; otherwise the programmes with a matching activity.
        $ids = null;
        if ($scope->isPartner()) {
            $ids = $scope->programmeIds ?? [];
            if ($this->filter->programmeId !== null) {
                $ids = array_values(array_intersect($ids, [$this->filter->programmeId]));
            }
        } elseif ($scope->mdaIds !== null || ! $this->filter->isEmpty()) {
            $ids = (clone $activities)->distinct()->pluck('programme_id')->all();
        }

        $query = Programme::query();
        if ($ids !== null) {
            $query->whereIn('id', $ids);
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
        $lf = $this->filter->ledgerFilters();

        return [
            'disbursed' => $this->ledger->scopedTotals($scope->mdaIds, $scope->programmeIds, $lf),
            'budget' => $this->ledger->scopedBudget($scope->mdaIds, $scope->programmeIds, $lf),
            'by_type' => $this->ledger->scopedGroup('benefit_type', $scope->mdaIds, $scope->programmeIds, $lf),
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
        $benefitByLga = $this->ledger->scopedGroup('lga', $scope->mdaIds, $scope->programmeIds, $this->filter->ledgerFilters());

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
            'net_unique_served' => $this->ledger->scopedDistinctBeneficiaries($scope->mdaIds, $scope->programmeIds, $this->filter->ledgerFilters()),
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
        $this->applyActivityFilter($activityQuery);
        $lf = $this->filter->ledgerFilters();
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

        $reach = $this->ledger->scopedReachByProgramme($scope->mdaIds, $programmeIds, $lf);
        $activityReach = $this->ledger->scopedReachByActivity($scope->mdaIds, $programmeIds, $lf);
        $spent = collect($this->ledger->scopedGroup('programme', $scope->mdaIds, $programmeIds, $lf))
            ->keyBy('key')
            ->map(fn (array $g) => (int) $g['total_value']);
        $activitySpent = collect($this->ledger->scopedGroup('activity', $scope->mdaIds, $programmeIds, $lf))
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
                    'mda' => $mdaNames[$a->owner_mda_id] ?? null,
                    'status' => $a->status->value,
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
        $activeMdas = (int) $this->applyBenefitFilter(Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->when($scope->mdaIds !== null, fn ($q) => $q->whereIn('mda_id', $scope->mdaIds)))
            ->distinct()->count('mda_id');

        $joint = (int) $this->applyBenefitFilter(Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->join('beneficiaries', 'benefits.beneficiary_id', '=', 'beneficiaries.id')
            ->whereColumn('benefits.mda_id', '!=', 'beneficiaries.owner_mda_id')
            ->when($scope->mdaIds !== null, fn ($q) => $q->whereIn('benefits.mda_id', $scope->mdaIds)), 'benefits.')
            ->distinct()->count('benefits.beneficiary_id');

        $refBase = Referral::query()->withoutGlobalScopes();
        if ($scope->mdaIds !== null) {
            $ids = $scope->mdaIds;
            $refBase->where(fn (Builder $w) => $w->whereIn('from_mda_id', $ids)->orWhereIn('to_mda_id', $ids));
        }
        $this->applyCoordinationFilter($refBase, 'from_mda_id', 'to_mda_id');
        $refTotal = (clone $refBase)->count();
        $refCompleted = (clone $refBase)->whereNotNull('completed_at')->count();

        $srBase = ServiceRequest::query();
        if ($scope->mdaIds !== null) {
            $ids = $scope->mdaIds;
            $srBase->where(fn (Builder $w) => $w->whereIn('from_mda_id', $ids)->orWhereIn('to_mda_id', $ids));
        }
        $this->applyCoordinationFilter($srBase, 'from_mda_id', 'to_mda_id');
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
        if ($this->filter->mdaId !== null) {
            $syncBase->where('owner_mda_id', $this->filter->mdaId);
            $connectorBase->where('owner_mda_id', $this->filter->mdaId);
        }
        [$syncFrom, $syncTo] = $this->filter->dateRange();
        if ($syncFrom !== null) {
            $syncBase->whereDate('created_at', '>=', $syncFrom);
        }
        if ($syncTo !== null) {
            $syncBase->whereDate('created_at', '<=', $syncTo);
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
        // Area/programme filter narrows which programmes qualify; an MDA filter is NOT
        // applied here — "joint" is inherently multi-MDA, so pinning one MDA is moot.
        if ($this->filter->programmeId !== null) {
            $query->where('programme_id', $this->filter->programmeId);
        }
        if ($this->filter->lga !== null) {
            $query->where('lga', $this->filter->lga);
        }
        if ($this->filter->ward !== null) {
            $query->where('ward', $this->filter->ward);
        }

        $joint = $query->select('programme_id')
            ->groupBy('programme_id')
            ->havingRaw('count(distinct owner_mda_id) > 1');

        return (int) DB::query()->fromSub($joint, 'joint')->count();
    }

    /**
     * PARTNER-FUNDING aggregates (Phase 6P) — activity-precise, over the activities a
     * partner actually funds (`activities.funding_partner_id`). Allocated = committed
     * funding on those activities; `delivered_value` = the recorded VALUE OF BENEFITS
     * DELIVERED under them (programme data) — **not treasury expenditure**; labelled so
     * downstream. Scoped to the partner, de-identified (counts + values only).
     *
     * @return array<string, mixed>|null
     */
    private function partnerFunding(DashboardScope $scope): ?array
    {
        $partnerId = $scope->partnerId;
        if ($partnerId === null) {
            return null;
        }

        $activities = $this->applyActivityFilter(
            Activity::query()->withoutGlobalScope(MdaScope::class)->where('funding_partner_id', $partnerId)
        )->get(['id', 'programme_id', 'owner_mda_id', 'lga', 'name', 'budget_amount', 'target_beneficiaries', 'status', 'starts_on', 'ends_on']);

        $activityIds = $activities->pluck('id')->all();
        $allocated = (int) $activities->sum('budget_amount');
        $target = (int) $activities->sum('target_beneficiaries');
        $fundedProgrammeIds = $activities->pluck('programme_id')->unique()->values()->all();
        $activeActivities = $activities->where('status', ActivityStatus::Active)->count();

        // Ledger constrained to the FUNDED activities only (never wider).
        $lf = array_merge($this->filter->ledgerFilters(), ['activity_ids' => $activityIds]);
        $delivered = $activityIds === [] ? 0 : (int) $this->ledger->scopedTotals(null, null, $lf)['total_value'];
        $netUnique = $activityIds === [] ? 0 : $this->ledger->scopedDistinctBeneficiaries(null, null, $lf);

        // Per-funded-programme breakdown + output indicators (Phase 6P "Programmes & Results").
        $outputs = $this->partnerOutputIndicators($activityIds);
        $programmes = $this->partnerProgrammes($activities, $activityIds, $outputs['by_programme']);

        // Absolute coverage (net-unique served per LGA/Ward) through funded activities.
        $coverageRows = [];
        $wardsCovered = 0;
        if ($activityIds !== []) {
            foreach ($this->ledger->scopedDistinctByArea('lga', null, null, $lf) as $raw => $count) {
                if ((string) $raw !== '') {
                    $coverageRows[] = ['lga' => (string) $raw, 'beneficiary_count' => $count];
                }
            }
            $wardsCovered = collect($this->ledger->scopedDistinctByArea('ward', null, null, $lf))
                ->filter(fn ($count, $raw) => (string) $raw !== '')->count();
        }

        return [
            'allocated' => $allocated,                 // committed funding (kobo)
            'delivered_value' => $delivered,           // value delivered to beneficiaries — NOT expenditure
            'remaining' => $allocated - $delivered,
            'utilization_rate' => $allocated > 0 ? round($delivered / $allocated, 4) : null,
            'funded_programmes' => count($fundedProgrammeIds),
            'funded_activities' => count($activityIds),
            'active_activities' => $activeActivities,
            'implementing_mdas' => $activities->pluck('owner_mda_id')->filter()->unique()->count(),
            'lgas_covered' => count($coverageRows),
            'wards_covered' => $wardsCovered,
            'net_unique_reached' => $netUnique,
            'target' => $target,
            'reach_vs_target' => $target > 0 ? round($netUnique / $target, 4) : null,
            'cost_per_beneficiary' => $netUnique > 0 ? (int) round($delivered / $netUnique) : null,
            'reach' => $this->partnerReach($activityIds),
            'coverage_bands' => $this->coverageBands($coverageRows),
            'funding_by_partner' => [[
                'partner_id' => $partnerId,
                'name' => User::query()->whereKey($partnerId)->value('name') ?? 'Partner',
                'allocated' => $allocated,
                'delivered_value' => $delivered,
                'net_unique_reached' => $netUnique,
                'funded_programmes' => count($fundedProgrammeIds),
            ]],
            'programme_overlap' => $this->programmeOverlap($partnerId, $fundedProgrammeIds, $activities),
            'programmes' => $programmes,                      // per funded programme (activity-precise)
            'output_indicators' => $outputs['rolled_up'],     // OUTPUTS ONLY, rolled up across programmes
            'registry' => $this->partnerRegistry($activityIds), // funded-programme beneficiaries (aggregate)
            'coordination' => $this->partnerCoordination($partnerId, $fundedProgrammeIds, [
                'allocated' => $allocated,
                'delivered_value' => $delivered,
                'net_unique_reached' => $netUnique,
                'funded_programmes' => count($fundedProgrammeIds),
            ], $activityIds),
        ];
    }

    /**
     * Reach demographics of the cohort SERVED through a partner's funded activities —
     * households, women (recorded female) and children (age band). CAPTURED fields only
     * (PWD/vulnerable are not held). Counts only, no PII.
     *
     * @param  list<string>  $activityIds
     * @return array<string, int>
     */
    private function partnerReach(array $activityIds): array
    {
        $empty = ['households_reached' => 0, 'women_reached' => 0, 'children_reached' => 0];
        if ($activityIds === []) {
            return $empty;
        }

        $servedIds = Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->where('status', '!=', BenefitStatus::Reversed->value)
            ->whereIn('activity_id', $activityIds)
            ->distinct()->pluck('beneficiary_id')->all();
        if ($servedIds === []) {
            return $empty;
        }

        $childBoundary = Carbon::today()->subYears(18)->toDateString();

        return [
            'households_reached' => (int) HouseholdMembership::query()->whereNull('left_at')
                ->whereIn('beneficiary_id', $servedIds)->distinct()->count('household_id'),
            'women_reached' => Beneficiary::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('id', $servedIds)->where('gender', 'female')->count(),
            'children_reached' => Beneficiary::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('id', $servedIds)->whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', $childBoundary)->count(),
        ];
    }

    /**
     * FUNDED-PROGRAMME BENEFICIARIES (Phase 6P "Registry" tab) — the aggregate registry
     * for a partner's funded cohort: beneficiaries ENROLLED IN or SERVED BY the funded
     * activities (activity-precise, union). De-identified counts only — never the raw
     * registry, never a beneficiary field. KPIs, captured-field demographics, a REDUCED
     * targeting funnel (Registered → Enrolled → Receiving; the eligible→selected steps
     * are omitted — no eligibility denominator / selection model), and data quality.
     *
     * @param  list<string>  $activityIds
     * @return array<string, mixed>
     */
    private function partnerRegistry(array $activityIds): array
    {
        $days = (int) config('reporting.current_period_days', 30);
        $emptyBands = ['1' => 0, '2-3' => 0, '4-6' => 0, '7+' => 0];
        $empty = [
            'total_individuals' => 0, 'total_households' => 0,
            'verified' => 0, 'pending' => 0, 'suspended' => 0,
            'duplicate_records' => 0, 'new_registrations' => 0, 'updated_records' => 0, 'period_days' => $days,
            'demographics' => [
                'by_gender' => [], 'gender_known' => 0, 'female_pct' => null, 'age_bands' => [], 'by_lga' => [],
                'household_size' => ['total_households' => 0, 'households_with_members' => 0, 'average_size' => null, 'bands' => $emptyBands],
            ],
            'funnel' => ['registered' => 0, 'enrolled' => 0, 'receiving' => 0],
            'quality' => [
                'verification_rate' => null, 'duplicate_rate' => null, 'data_completeness' => null, 'nin_linkage' => null,
                'missing' => ['nin' => 0, 'phone' => 0, 'date_of_birth' => 0, 'gender' => 0, 'lga' => 0],
            ],
        ];
        if ($activityIds === []) {
            return $empty;
        }

        // Funded cohort = beneficiaries enrolled in ∪ served by the funded activities.
        $enrolledIds = Enrollment::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('activity_id', $activityIds)->whereNotNull('beneficiary_id')
            ->distinct()->pluck('beneficiary_id')->all();
        $servedIds = Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->where('status', '!=', BenefitStatus::Reversed->value)
            ->whereIn('activity_id', $activityIds)->distinct()->pluck('beneficiary_id')->all();
        $cohortIds = array_values(array_unique(array_merge($enrolledIds, $servedIds)));
        if ($cohortIds === []) {
            return $empty;
        }

        $base = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->whereIn('id', $cohortIds);
        $total = (clone $base)->count();
        $byStatus = $this->countBy($base, 'status');
        $verified = $byStatus['active'] ?? 0;
        $byGender = $this->countBy($base, 'gender');
        $knownGender = $total - ($byGender['unspecified'] ?? 0);
        $female = $byGender['female'] ?? 0;

        $householdIds = HouseholdMembership::query()->whereNull('left_at')
            ->whereIn('beneficiary_id', $cohortIds)->distinct()->pluck('household_id')->all();

        $since = Carbon::now()->subDays($days);
        $newReg = (clone $base)->whereDate('registration_date', '>=', $since->toDateString())->count();
        $updated = (clone $base)->where('updated_at', '>=', $since)
            ->whereColumn('updated_at', '>', 'created_at')->count();

        $withNin = (clone $base)->whereNotNull('nin_hash')->count();
        $withPhone = (clone $base)->whereNotNull('phone')->count();
        $withDob = (clone $base)->whereNotNull('date_of_birth')->count();
        $withGender = (clone $base)->whereNotNull('gender')->count();
        $withLga = (clone $base)->whereNotNull('lga')->count();
        $withId = (clone $base)->where(fn (Builder $w) => $w->whereNotNull('nin_hash')->orWhereNotNull('bvn_hash'))->count();
        $ratio = fn (int $n): ?float => $total > 0 ? round($n / $total, 4) : null;

        // Duplicate records SURFACED for the cohort (import match bands) — an aggregate
        // data-quality signal about the funded beneficiaries only; no PII, no other MDA's data.
        $dupRecords = (int) DB::table('import_rows')
            ->whereIn('match_band', ['exact', 'probable'])
            ->where(fn ($q) => $q->whereIn('beneficiary_id', $cohortIds)->orWhereIn('resolved_beneficiary_id', $cohortIds))
            ->count();

        return [
            'total_individuals' => $total,
            'total_households' => count($householdIds),
            'verified' => $verified,
            'pending' => $byStatus['flagged'] ?? 0,
            'suspended' => $byStatus['suspended'] ?? 0,
            'duplicate_records' => $dupRecords,
            'new_registrations' => $newReg,
            'updated_records' => $updated,
            'period_days' => $days,
            'demographics' => [
                'by_gender' => $byGender,
                'gender_known' => $knownGender,
                'female_pct' => $knownGender > 0 ? round($female / $knownGender, 4) : null,
                'age_bands' => $this->ageBands($base),
                'by_lga' => $this->countBy($base, 'lga'),
                'household_size' => $this->cohortHouseholdSize($householdIds),
            ],
            'funnel' => [
                'registered' => $total,          // beneficiaries on record for the funded activities
                'enrolled' => count($enrolledIds), // distinct beneficiaries enrolled
                'receiving' => count($servedIds),  // distinct beneficiaries served (net-unique)
            ],
            'quality' => [
                'verification_rate' => $ratio($verified),
                'duplicate_rate' => $total > 0 ? round($dupRecords / $total, 4) : null,
                'data_completeness' => $total > 0
                    ? round(($withId + $withPhone + $withDob + $withGender + $withLga) / (5 * $total), 4)
                    : null,
                'nin_linkage' => $ratio($withNin),
                'missing' => [
                    'nin' => $total - $withNin,
                    'phone' => $total - $withPhone,
                    'date_of_birth' => $total - $withDob,
                    'gender' => $total - $withGender,
                    'lga' => $total - $withLga,
                ],
            ],
        ];
    }

    /**
     * Household-size distribution (banded 1 / 2–3 / 4–6 / 7+) over a set of household ids
     * — a field we HAVE (active memberships per household). Used for the partner cohort,
     * whose households come from its served/enrolled members (a partner owns no households).
     *
     * @param  list<string>  $householdIds
     * @return array<string, mixed>
     */
    private function cohortHouseholdSize(array $householdIds): array
    {
        $bands = ['1' => 0, '2-3' => 0, '4-6' => 0, '7+' => 0];
        if ($householdIds === []) {
            return ['total_households' => 0, 'households_with_members' => 0, 'average_size' => null, 'bands' => $bands];
        }

        $sizes = HouseholdMembership::query()->whereNull('left_at')
            ->whereIn('household_id', $householdIds)
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
            'total_households' => count($householdIds),
            'households_with_members' => $withMembers,
            'average_size' => $withMembers > 0 ? round($members / $withMembers, 2) : null,
            'bands' => $bands,
        ];
    }

    /**
     * Per-FUNDED-programme results (Phase 6P "Programmes & Results"), ACTIVITY-PRECISE:
     * only the partner's funded activities count toward each programme's budget allocated,
     * value DELIVERED (recorded delivery value, NOT treasury expenditure), reach, coverage
     * (absolute), completion, interventions (benefit-record count), average benefit value,
     * cost per beneficiary, a monthly delivery-rate series and a four-state delivery status.
     * De-identified aggregates only; the ledger is constrained to the funded activities.
     *
     * @param  Collection<int, Activity>  $activities  the partner's funded activities
     * @param  list<string>  $activityIds
     * @param  array<string, array<int, array<string, int|string|null>>>  $outputByProgramme
     * @return array<int, array<string, mixed>>
     */
    private function partnerProgrammes(Collection $activities, array $activityIds, array $outputByProgramme): array
    {
        if ($activityIds === []) {
            return [];
        }

        $base = $this->filter->ledgerFilters();
        $lf = array_merge($base, ['activity_ids' => $activityIds]);
        $reachByProg = $this->ledger->scopedReachByProgramme(null, null, $lf);
        $reachByAct = $this->ledger->scopedReachByActivity(null, null, $lf);
        $valueByProg = collect($this->ledger->scopedGroup('programme', null, null, $lf))->keyBy('key');
        $valueByAct = collect($this->ledger->scopedGroup('activity', null, null, $lf))->keyBy('key');

        $programmeIds = $activities->pluck('programme_id')->unique()->values()->all();
        $programmes = Programme::query()->whereIn('id', $programmeIds)->get(['id', 'name', 'type', 'status'])->keyBy('id');
        $mdaNames = Mda::query()
            ->whereIn('id', $activities->pluck('owner_mda_id')->filter()->unique()->all())
            ->pluck('name', 'id');

        $months = (int) config('reporting.trend_months', 12);
        $today = Carbon::today()->toDateString();
        $green = (float) config('reporting.programme_traffic_light.green_min', 0.8);
        $yellow = (float) config('reporting.programme_traffic_light.yellow_min', 0.5);
        $light = fn (?float $c): string => $c === null
            ? 'unrated'
            : ($c >= $green ? 'green' : ($c >= $yellow ? 'yellow' : 'red'));
        $day = fn ($d): ?string => $d instanceof \DateTimeInterface ? $d->format('Y-m-d') : ($d === null ? null : (string) $d);

        $out = [];
        foreach ($activities->groupBy('programme_id') as $rawPid => $progActivities) {
            $pid = (string) $rawPid;
            $ids = $progActivities->pluck('id')->all();
            $allocated = (int) $progActivities->sum('budget_amount');
            $target = (int) $progActivities->sum('target_beneficiaries');
            $progValue = $valueByProg->get($pid) ?? [];
            $delivered = (int) ($progValue['total_value'] ?? 0);
            $interventions = (int) ($progValue['benefit_count'] ?? 0);
            $reached = (int) ($reachByProg[$pid] ?? 0);
            $completion = $target > 0 ? round($reached / $target, 4) : null;

            $starts = $progActivities->pluck('starts_on')->filter()->map($day)->all();
            $ends = $progActivities->pluck('ends_on')->filter()->map($day)->all();
            $endDate = $ends === [] ? null : max($ends);
            $programme = $programmes[$pid] ?? null;

            $activityRows = $progActivities->map(function (Activity $a) use ($reachByAct, $valueByAct, $mdaNames, $light) {
                $aReached = (int) ($reachByAct[$a->id] ?? 0);
                $aTarget = (int) ($a->target_beneficiaries ?? 0);
                $aAllocated = (int) ($a->budget_amount ?? 0);
                $aDelivered = (int) (($valueByAct->get($a->id) ?? [])['total_value'] ?? 0);
                $aCompletion = $aTarget > 0 ? round($aReached / $aTarget, 4) : null;

                return [
                    'activity_id' => $a->id,
                    'name' => $a->name,
                    'mda' => $mdaNames[$a->owner_mda_id] ?? null,
                    'status' => $a->status->value,
                    'target' => $aTarget,
                    'reached' => $aReached,
                    'completion_rate' => $aCompletion,
                    'coverage_absolute' => $aReached,
                    'allocated' => $aAllocated,
                    'delivered_value' => $aDelivered,
                    'remaining' => $aAllocated - $aDelivered,
                    'cost_per_beneficiary' => $aReached > 0 ? (int) round($aDelivered / $aReached) : null,
                    'traffic_light' => $light($aCompletion),
                ];
            })->values()->all();

            $out[] = [
                'programme_id' => $pid,
                'name' => $programme?->name,
                'type' => $programme?->type instanceof \BackedEnum ? $programme->type->value : $programme?->type,
                'status' => $programme?->status instanceof \BackedEnum ? $programme->status->value : $programme?->status,
                'mdas' => $progActivities->pluck('owner_mda_id')->filter()->unique()->values()
                    ->map(fn ($mid) => ['id' => $mid, 'name' => $mdaNames[$mid] ?? null])->all(),
                'start_date' => $starts === [] ? null : min($starts),
                'end_date' => $endDate,
                'allocated' => $allocated,
                'delivered_value' => $delivered,
                'remaining' => $allocated - $delivered,
                'utilization_rate' => $allocated > 0 ? round($delivered / $allocated, 4) : null,
                'target' => $target,
                'reached' => $reached,
                'coverage_absolute' => $reached,
                'completion_rate' => $completion,
                'interventions' => $interventions,
                'avg_benefit_value' => $interventions > 0 ? (int) round($delivered / $interventions) : null,
                'cost_per_beneficiary' => $reached > 0 ? (int) round($delivered / $reached) : null,
                'delivery_series' => $this->zeroFilledSeries(
                    $this->ledger->scopedDisbursementSeries(null, null, $months, array_merge($base, ['activity_ids' => $ids])),
                    $months
                ),
                'status_light' => $this->programmeStatusLight($completion, $endDate, $today),
                'output_indicators' => $outputByProgramme[$pid] ?? [],
                'activities' => $activityRows,
            ];
        }

        usort($out, fn (array $a, array $b): int => $b['delivered_value'] <=> $a['delivered_value']);

        return $out;
    }

    /**
     * Four-state funded-programme delivery status (Phase 6P) from completion + timeline:
     * past the delivery end date → Completed (if completion ≥ completed_min) else Delayed;
     * still in timeline → On Track / At Risk / Delayed by completion band. Thresholds are
     * configurable (config/reporting.php programme_status). No target → "unrated".
     */
    private function programmeStatusLight(?float $completion, ?string $endDate, string $today): string
    {
        if ($completion === null) {
            return 'unrated';
        }

        $completed = (float) config('reporting.programme_status.completed_min', 0.9);
        $onTrack = (float) config('reporting.programme_status.on_track_min', 0.8);
        $atRisk = (float) config('reporting.programme_status.at_risk_min', 0.5);

        if ($endDate !== null && $endDate < $today) {
            return $completion >= $completed ? 'completed' : 'delayed';
        }

        return match (true) {
            $completion >= $onTrack => 'on_track',
            $completion >= $atRisk => 'at_risk',
            default => 'delayed',
        };
    }

    /**
     * OUTPUT INDICATORS (Phase 6P) — counts of INTERVENTIONS (benefit records) delivered
     * under a partner's funded activities, by benefit TYPE and captured demographic
     * (gender, age). OUTPUTS ONLY — interventions delivered — never outcomes (poverty,
     * income, attendance), which require external evaluation data. Counts only, no PII.
     * Returns a per-programme map and a rolled-up total across all funded programmes.
     *
     * @param  list<string>  $activityIds
     * @return array{by_programme: array<string, array<int, array<string, int|string|null>>>, rolled_up: array<int, array<string, int|string|null>>}
     */
    private function partnerOutputIndicators(array $activityIds): array
    {
        if ($activityIds === []) {
            return ['by_programme' => [], 'rolled_up' => []];
        }

        $rows = Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->where('status', '!=', BenefitStatus::Reversed->value)
            ->whereIn('activity_id', $activityIds)
            ->get(['programme_id', 'benefit_type', 'beneficiary_id']);
        if ($rows->isEmpty()) {
            return ['by_programme' => [], 'rolled_up' => []];
        }

        $childBoundary = Carbon::today()->subYears(18)->toDateString();
        $demographics = Beneficiary::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('id', $rows->pluck('beneficiary_id')->unique()->all())
            ->get(['id', 'gender', 'date_of_birth'])
            ->keyBy('id');

        $typeValue = fn (Benefit $b): string => (string) $b->benefit_type->value;

        /**
         * @param  Collection<int, Benefit>  $group
         * @return array<int, array<string, int|string|null>>
         */
        $summarise = function (Collection $group) use ($typeValue, $demographics, $childBoundary): array {
            $byType = [];
            foreach ($group->groupBy($typeValue) as $type => $typeRows) {
                $beneficiaryIds = $typeRows->pluck('beneficiary_id')->unique();
                $women = $beneficiaryIds->filter(
                    fn ($id) => $demographics->get($id)?->gender?->value === 'female'
                )->count();
                $children = $beneficiaryIds->filter(function ($id) use ($demographics, $childBoundary) {
                    $dob = $demographics->get($id)?->date_of_birth;

                    return $dob !== null && $dob->toDateString() > $childBoundary;
                })->count();

                $byType[] = [
                    'benefit_type' => (string) $type,
                    'interventions' => $typeRows->count(),
                    'beneficiaries' => $beneficiaryIds->count(),
                    'women' => $women,
                    'children' => $children,
                ];
            }
            usort($byType, fn (array $a, array $b): int => (int) $b['interventions'] <=> (int) $a['interventions']);

            return $byType;
        };

        $byProgramme = [];
        foreach ($rows->groupBy('programme_id') as $rawPid => $progRows) {
            $byProgramme[(string) $rawPid] = $summarise($progRows);
        }

        return ['by_programme' => $byProgramme, 'rolled_up' => $summarise($rows)];
    }

    /**
     * Zero-fill a {'YYYY-MM' => value} map to the full last-N-months label list, so a
     * delivery series always has one point per month (gaps rendered as zero).
     *
     * @param  array<string, int>  $map
     * @return array<int, array{month: string, value: int}>
     */
    private function zeroFilledSeries(array $map, int $months): array
    {
        return array_map(
            fn (string $m): array => ['month' => $m, 'value' => (int) ($map[$m] ?? 0)],
            $this->monthLabels($months)
        );
    }

    /**
     * PARTNER COORDINATION (Phase 6P "Coordination" tab) — the actor landscape AROUND a
     * partner's funded programmes: the funding organisations, government agencies (MDAs)
     * and implementing agencies active in them; a funding-by-partner table (amounts for
     * the CALLER only — a partner never sees another funder's money); the MDA landscape;
     * and data-sharing / sync health for the implementing agencies. Programme overlap
     * (the tab's headline) is served by {@see programmeOverlap()} on the same block.
     *
     * @param  list<string>  $fundedProgrammeIds
     * @param  array{allocated:int,delivered_value:int,net_unique_reached:int,funded_programmes:int}  $selfTotals
     * @param  list<string>  $callerActivityIds
     * @return array<string, mixed>
     */
    private function partnerCoordination(string $partnerId, array $fundedProgrammeIds, array $selfTotals, array $callerActivityIds): array
    {
        $empty = [
            'landscape' => ['funders' => 0, 'government_agencies' => 0, 'implementing_agencies' => 0],
            'funding_by_partner' => [],
            'agencies' => [],
            'data_sharing' => ['agencies_integrated' => 0, 'connectors' => 0, 'sources' => [], 'total_runs' => 0, 'succeeded' => 0, 'failed' => 0, 'last_run_at' => null, 'api_registrations' => 0],
        ];
        if ($fundedProgrammeIds === []) {
            return $empty;
        }

        // Every activity in the caller's funded programmes (ALL funders/MDAs) — the landscape base.
        $acts = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('programme_id', $fundedProgrammeIds)
            ->get(['id', 'programme_id', 'owner_mda_id', 'funding_partner_id']);

        // Funders (funding organisations): programme sets per funder, from activity attribution
        // + ProgrammeFunder, restricted to the caller's funded programmes.
        $funderProgrammes = [];
        foreach ($acts as $a) {
            if ($a->funding_partner_id !== null) {
                $funderProgrammes[$a->funding_partner_id][$a->programme_id] = true;
            }
        }
        foreach (ProgrammeFunder::query()->whereIn('programme_id', $fundedProgrammeIds)->get(['user_id', 'programme_id']) as $funder) {
            $funderProgrammes[$funder->user_id][$funder->programme_id] = true;
        }
        $funderProgrammes[$partnerId] ??= array_fill_keys($fundedProgrammeIds, true);
        $funderNames = User::query()->whereIn('id', array_keys($funderProgrammes))->pluck('name', 'id');

        // Funding-by-partner: the caller with real figures; every co-funder WITHOUT amounts
        // (a partner sees only its own money) — name + count of shared programmes only.
        $fundingByPartner = [[
            'partner_id' => $partnerId,
            'name' => $funderNames[$partnerId] ?? 'You',
            'is_self' => true,
            'allocated' => $selfTotals['allocated'],
            'delivered_value' => $selfTotals['delivered_value'],
            'net_unique_reached' => $selfTotals['net_unique_reached'],
            'funded_programmes' => $selfTotals['funded_programmes'],
            'shared_programmes' => $selfTotals['funded_programmes'],
        ]];
        foreach ($funderProgrammes as $funderId => $programmeSet) {
            if ((string) $funderId === $partnerId) {
                continue;
            }
            $fundingByPartner[] = [
                'partner_id' => (string) $funderId,
                'name' => $funderNames[$funderId] ?? 'Partner',
                'is_self' => false,
                'allocated' => null,
                'delivered_value' => null,
                'net_unique_reached' => null,
                'funded_programmes' => null,
                'shared_programmes' => count($programmeSet),
            ];
        }

        // Government agencies (MDAs) implementing activities in the funded programmes.
        $mdaIds = $acts->pluck('owner_mda_id')->filter()->unique()->values()->all();
        $mdaNames = Mda::query()->whereIn('id', $mdaIds)->pluck('name', 'id');
        $agencies = [];
        foreach ($acts->groupBy('owner_mda_id') as $rawMid => $mdaActs) {
            $mid = (string) $rawMid;
            if ($mid === '') {
                continue;
            }
            $agencies[] = [
                'id' => $mid,
                'name' => $mdaNames[$mid] ?? null,
                'activities' => $mdaActs->count(),
                'programmes' => $mdaActs->pluck('programme_id')->unique()->count(),
            ];
        }
        usort($agencies, fn (array $a, array $b): int => $b['activities'] <=> $a['activities']);

        // Implementing agencies = distinct MDAs DELIVERING benefits under the caller's funded activities.
        $implementing = $callerActivityIds === [] ? 0 : (int) Benefit::query()->withoutGlobalScope(MdaScope::class)
            ->where('status', '!=', BenefitStatus::Reversed->value)
            ->whereIn('activity_id', $callerActivityIds)
            ->distinct()->count('mda_id');

        // Data sharing / sync health for the implementing MDAs (Phase 7 sync status, reused).
        $apiRegistrations = 0;
        if ($callerActivityIds !== []) {
            $servedIds = Benefit::query()->withoutGlobalScope(MdaScope::class)
                ->where('status', '!=', BenefitStatus::Reversed->value)
                ->whereIn('activity_id', $callerActivityIds)->distinct()->pluck('beneficiary_id')->all();
            $apiRegistrations = $servedIds === [] ? 0 : Beneficiary::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('id', $servedIds)->where('registration_source', 'api')->count();
        }
        $connectorBase = SyncConnector::query()->withoutGlobalScopes()->whereIn('owner_mda_id', $mdaIds);
        $runBase = SyncRun::query()->withoutGlobalScopes()->whereIn('owner_mda_id', $mdaIds);
        $lastRun = $mdaIds === [] ? null : (clone $runBase)->latest('created_at')->first();

        return [
            'landscape' => [
                'funders' => count($funderProgrammes),
                'government_agencies' => count($mdaIds),
                'implementing_agencies' => $implementing,
            ],
            'funding_by_partner' => $fundingByPartner,
            'agencies' => $agencies,
            'data_sharing' => [
                'agencies_integrated' => $mdaIds === [] ? 0 : (int) (clone $connectorBase)->distinct()->count('owner_mda_id'),
                'connectors' => $mdaIds === [] ? 0 : (clone $connectorBase)->count(),
                'sources' => $mdaIds === [] ? [] : (clone $connectorBase)->distinct()->pluck('source')->filter()->values()->all(),
                'total_runs' => $mdaIds === [] ? 0 : (clone $runBase)->count(),
                'succeeded' => $mdaIds === [] ? 0 : (clone $runBase)->where('status', 'completed')->count(),
                'failed' => $mdaIds === [] ? 0 : (clone $runBase)->where('status', 'failed')->count(),
                'last_run_at' => $lastRun?->created_at?->toIso8601String(),
                'api_registrations' => $apiRegistrations,
            ],
        ];
    }

    /**
     * PROGRAMME OVERLAP (Phase 6P) — where a partner's funded (catalog programme × LGA)
     * cell is ALSO served, in the same LGA, by a DIFFERENT funder or a DIFFERENT MDA.
     * A coordination signal only: it exposes the existence + count of other funders/MDAs,
     * never their amounts (a partner sees only their own money).
     *
     * @param  list<string>  $fundedProgrammeIds
     * @param  Collection<int, Activity>  $partnerActivities
     * @return array<string, mixed>
     */
    private function programmeOverlap(string $partnerId, array $fundedProgrammeIds, $partnerActivities): array
    {
        if ($fundedProgrammeIds === []) {
            return ['count' => 0, 'cells' => []];
        }

        $partnerCells = $partnerActivities
            ->filter(fn (Activity $a) => $a->lga !== null && $a->lga !== '')
            ->map(fn (Activity $a) => $a->programme_id.'|'.$a->lga)->unique()->values()->all();
        if ($partnerCells === []) {
            return ['count' => 0, 'cells' => []];
        }

        $byCell = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('programme_id', $fundedProgrammeIds)->whereNotNull('lga')
            ->get(['programme_id', 'lga', 'owner_mda_id', 'funding_partner_id'])
            ->groupBy(fn (Activity $a) => $a->programme_id.'|'.$a->lga);
        $names = Programme::query()->whereIn('id', $fundedProgrammeIds)->pluck('name', 'id');

        $cells = [];
        foreach ($partnerCells as $cellKey) {
            $rows = $byCell[$cellKey] ?? collect();
            $otherFunders = $rows->pluck('funding_partner_id')->filter()
                ->reject(fn ($id) => (string) $id === $partnerId)->unique()->count();
            $otherMdas = $rows->reject(fn (Activity $a) => (string) $a->funding_partner_id === $partnerId)
                ->pluck('owner_mda_id')->filter()->unique()->count();

            if ($otherFunders > 0 || $otherMdas > 0) {
                [$pid, $lga] = explode('|', (string) $cellKey, 2);
                $cells[] = [
                    'programme_id' => $pid,
                    'programme' => $names[$pid] ?? null,
                    'lga' => $lga,
                    'other_funders' => $otherFunders,
                    'other_mdas' => $otherMdas,
                ];
            }
        }

        return ['count' => count($cells), 'cells' => $cells];
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
        $lf = $this->filter->ledgerFilters();
        $fundingFor = fn (array $ids): int => (int) $this->applyActivityFilter(
            Activity::query()->withoutGlobalScope(MdaScope::class)->whereIn('programme_id', $ids)
        )->sum('budget_amount');

        $list = $rows->groupBy('user_id')->map(function ($partnerRows, $userId) use ($names, $fundingFor, $lf) {
            $pids = $partnerRows->pluck('programme_id')->unique()->values()->all();

            return [
                'partner_id' => (string) $userId,
                'name' => $names[$userId] ?? 'Partner',
                'funded_programmes' => count($pids),
                'beneficiaries_served' => $this->ledger->scopedDistinctBeneficiaries(null, $pids, $lf),
                'funding_allocated' => $fundingFor($pids),
            ];
        })->sortByDesc('funding_allocated')->values()->all();

        return [
            'count' => $rows->pluck('user_id')->unique()->count(),
            'funded_programmes' => count($programmeIds),
            'beneficiaries_served' => $this->ledger->scopedDistinctBeneficiaries(null, $programmeIds, $lf),
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
        $disbursement = $this->ledger->scopedDisbursementSeries($scope->mdaIds, $scope->programmeIds, $months, $this->filter->ledgerFilters());
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
     * runs (has activities for); state-wide = the whole catalog. A programme filter
     * intersects the result (never widens it); an MDA/area filter narrows the MDA set.
     *
     * @return list<string>
     */
    private function programmeIdsInScope(DashboardScope $scope): array
    {
        if ($scope->isPartner()) {
            $ids = $scope->programmeIds ?? [];
        } elseif ($scope->mdaIds !== null) {
            $ids = $this->applyActivityFilter(
                Activity::query()->withoutGlobalScope(MdaScope::class)->whereIn('owner_mda_id', $scope->mdaIds)
            )->distinct()->pluck('programme_id')->all();
        } elseif (! $this->filter->isEmpty()) {
            $ids = $this->applyActivityFilter(Activity::query()->withoutGlobalScope(MdaScope::class))
                ->distinct()->pluck('programme_id')->all();
        } else {
            $ids = Programme::query()->pluck('id')->all();
        }

        if ($this->filter->programmeId !== null) {
            $ids = array_values(array_intersect($ids, [$this->filter->programmeId]));
        }

        return $ids;
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

            $query->whereIn('id', $servedIds);
        } elseif ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }

        return $this->applyBeneficiaryFilter($query);
    }

    /**
     * Apply the cross-cutting filter to a beneficiary query (registration date range,
     * area, owning MDA, and — for a programme filter — restrict to those served by it).
     *
     * @param  Builder<Beneficiary>  $query
     * @return Builder<Beneficiary>
     */
    private function applyBeneficiaryFilter(Builder $query): Builder
    {
        $f = $this->filter;
        if ($f->mdaId !== null) {
            $query->where('owner_mda_id', $f->mdaId);
        }
        if ($f->lga !== null) {
            $query->where('lga', $f->lga);
        }
        if ($f->ward !== null) {
            $query->where('ward', $f->ward);
        }
        [$from, $to] = $f->dateRange();
        if ($from !== null) {
            $query->whereDate('registration_date', '>=', $from);
        }
        if ($to !== null) {
            $query->whereDate('registration_date', '<=', $to);
        }
        if ($f->programmeId !== null) {
            $query->whereIn('id', Benefit::query()->withoutGlobalScope(MdaScope::class)
                ->where('programme_id', $f->programmeId)->select('beneficiary_id'));
        }

        return $query;
    }

    /**
     * Apply the cross-cutting filter to an activity query (programme, owning MDA, area).
     *
     * @param  Builder<Activity>  $query
     * @return Builder<Activity>
     */
    private function applyActivityFilter(Builder $query): Builder
    {
        $f = $this->filter;
        if ($f->programmeId !== null) {
            $query->where('programme_id', $f->programmeId);
        }
        if ($f->mdaId !== null) {
            $query->where('owner_mda_id', $f->mdaId);
        }
        if ($f->lga !== null) {
            $query->where('lga', $f->lga);
        }
        if ($f->ward !== null) {
            $query->where('ward', $f->ward);
        }

        return $query;
    }

    /**
     * Apply the cross-cutting filter to a benefit-ledger query (programme, delivering
     * MDA, area, delivery-date range). `$prefix` qualifies the columns for joined queries.
     *
     * @param  Builder<Benefit>  $query
     * @return Builder<Benefit>
     */
    private function applyBenefitFilter(Builder $query, string $prefix = ''): Builder
    {
        $f = $this->filter;
        if ($f->programmeId !== null) {
            $query->where("{$prefix}programme_id", $f->programmeId);
        }
        if ($f->mdaId !== null) {
            $query->where("{$prefix}mda_id", $f->mdaId);
        }
        if ($f->lga !== null) {
            $query->where("{$prefix}lga", $f->lga);
        }
        if ($f->ward !== null) {
            $query->where("{$prefix}ward", $f->ward);
        }
        [$from, $to] = $f->dateRange();
        if ($from !== null) {
            $query->whereDate("{$prefix}delivery_date", '>=', $from);
        }
        if ($to !== null) {
            $query->whereDate("{$prefix}delivery_date", '<=', $to);
        }

        return $query;
    }

    /**
     * Apply the MDA + date parts of the filter to a coordination record query
     * (referrals / service requests) that has from/to MDA columns and a `created_at`.
     *
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     */
    private function applyCoordinationFilter(Builder $query, string $fromCol, string $toCol): Builder
    {
        $f = $this->filter;
        if ($f->mdaId !== null) {
            $query->where(fn (Builder $w) => $w->where($fromCol, $f->mdaId)->orWhere($toCol, $f->mdaId));
        }
        [$from, $to] = $f->dateRange();
        if ($from !== null) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to !== null) {
            $query->whereDate('created_at', '<=', $to);
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

        $f = $this->filter;
        if ($f->mdaId !== null) {
            $query->where('owner_mda_id', $f->mdaId);
        }
        if ($f->lga !== null) {
            $query->where('lga', $f->lga);
        }
        if ($f->ward !== null) {
            $query->where('ward', $f->ward);
        }
        [$from, $to] = $f->dateRange();
        if ($from !== null) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to !== null) {
            $query->whereDate('created_at', '<=', $to);
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
