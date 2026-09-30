<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Reporting\Support\DashboardFilter;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * How an MDA's projects are doing in one month (FR-RPT-12b).
 *
 * THE RULE THIS CLASS EXISTS TO ENFORCE — every figure is either FLOW or CUMULATIVE,
 * and the two are never divided into each other:
 *
 *   FLOW        what happened IN the month. Comes from the ledger with a period filter:
 *               people reached, value delivered. Comparable against last month.
 *   CUMULATIVE  where the project stands overall. Reach and spend to date, measured
 *               against `target_beneficiaries` and `budget_amount`, which are LIFETIME
 *               figures on the activity row and are not period-filtered.
 *
 * Mixing them is the trap. `reached_this_month / lifetime_target` is a meaningless
 * ratio that lands near zero for a perfectly healthy project — a twelve-month activity
 * delivering exactly on plan would report ~8% every month and light up red every time.
 * So {@see self::trafficLight()} is computed ONLY from cumulative figures, and the
 * monthly numbers are never given a denominator.
 *
 * The month-on-month comparison needs no stored history: it is the same aggregation run
 * twice with two {@see DashboardFilter}s. `DashboardSnapshot` is a per-scope cache, not
 * a time series, and would have been the wrong thing to reach for.
 */
class MonthlyProjectService
{
    public function __construct(private readonly LedgerAggregator $ledger) {}

    /**
     * The month a report covers when nobody names one: the last COMPLETE month.
     *
     * Never the current month. A run on the 3rd would otherwise compare three days
     * against a full month and report every project as collapsing.
     *
     * @return array{int, int} [year, month]
     */
    public static function lastCompleteMonth(?Carbon $now = null): array
    {
        $month = ($now ?? Carbon::now())->copy()->startOfMonth()->subMonth();

        return [(int) $month->year, (int) $month->month];
    }

    /**
     * @return array{
     *     period: array{year: int, month: int, label: string, starts_on: string, ends_on: string},
     *     previous: array{year: int, month: int, label: string},
     *     totals: array{activities: int, active: int, reached: int, value: int},
     *     previous_totals: array{reached: int, value: int},
     *     projects: list<array<string, mixed>>,
     *     attention: list<array{name: string, reason: string}>,
     *     ending_soon: list<array{name: string, ends_on: string}>,
     * }
     */
    public function build(DashboardScope $scope, int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $previous = $start->copy()->subMonth();

        $activities = $this->activitiesInScope($scope);
        $mdaNames = Mda::query()->withoutGlobalScope(MdaScope::class)
            ->whereIn('id', $activities->pluck('owner_mda_id')->filter()->unique()->all())
            ->pluck('name', 'id');
        $programmeNames = Programme::query()->withoutGlobalScope(MdaScope::class)->withArchived()
            ->whereIn('id', $activities->pluck('programme_id')->filter()->unique()->all())
            ->pluck('name', 'id');

        $mdaIds = $scope->isPartner() ? null : $scope->mdaIds;
        $programmeIds = $scope->programmeIds;

        // Three aggregations: this month, last month, and all time. The first two are
        // the flow figures, the third is what the traffic light is allowed to see.
        $thisMonth = $this->ledgerFor($mdaIds, $programmeIds, new DashboardFilter(year: $year, month: $month));
        $lastMonth = $this->ledgerFor($mdaIds, $programmeIds, new DashboardFilter(year: (int) $previous->year, month: (int) $previous->month));
        $toDate = $this->ledgerFor($mdaIds, $programmeIds, DashboardFilter::none());

        $projects = [];
        $attention = [];
        $endingSoon = [];
        $endOfNext = $start->copy()->addMonth()->endOfMonth();

        foreach ($activities as $activity) {
            $id = (string) $activity->id;
            $tracksPeople = (bool) $activity->involves_beneficiaries;

            $monthReached = $tracksPeople ? ($thisMonth['reach'][$id] ?? 0) : null;
            $monthValue = $thisMonth['value'][$id] ?? 0;
            $dateReached = $tracksPeople ? ($toDate['reach'][$id] ?? 0) : null;
            $dateValue = $toDate['value'][$id] ?? 0;

            $target = $tracksPeople ? (int) ($activity->target_beneficiaries ?? 0) : null;
            $budget = (int) ($activity->budget_amount ?? 0);

            // Cumulative, and only cumulative. See the class docblock.
            $completion = ($target !== null && $target > 0 && $dateReached !== null)
                ? round($dateReached / $target, 4)
                : null;

            $projects[] = [
                'activity_id' => $id,
                'name' => (string) $activity->name,
                'programme' => $programmeNames[$activity->programme_id] ?? null,
                'mda' => $mdaNames[$activity->owner_mda_id] ?? null,
                'status' => $activity->status->value,
                'tracks_people' => $tracksPeople,
                'starts_on' => $this->day($activity->starts_on),
                'ends_on' => $this->day($activity->ends_on),
                'month' => ['reached' => $monthReached, 'value' => $monthValue],
                'to_date' => ['reached' => $dateReached, 'value' => $dateValue],
                'target' => $target,
                'completion_rate' => $completion,
                'budget' => ['allocated' => $budget, 'spent' => $dateValue, 'remaining' => $budget - $dateValue],
                'traffic_light' => $tracksPeople ? $this->trafficLight($completion) : null,
            ];

            foreach ($this->concerns($activity, $start, $monthValue, $monthReached, $completion, $budget, $dateValue) as $reason) {
                $attention[] = ['name' => (string) $activity->name, 'reason' => $reason];
            }

            $nextMonthStart = $start->copy()->addMonth()->startOfMonth();
            if ($activity->ends_on !== null
                && $activity->status !== ActivityStatus::Completed
                && $activity->ends_on->gte($nextMonthStart)
                && $activity->ends_on->lte($endOfNext)) {
                $endingSoon[] = ['name' => (string) $activity->name, 'ends_on' => (string) $this->day($activity->ends_on)];
            }
        }

        // Sorted by what was delivered this month, so the report opens on what moved.
        usort($projects, static fn (array $a, array $b): int => ($b['month']['value'] <=> $a['month']['value'])
            ?: strcmp((string) $a['name'], (string) $b['name']));

        return [
            'period' => [
                'year' => $year,
                'month' => $month,
                'label' => $start->format('F Y'),
                'starts_on' => $start->toDateString(),
                'ends_on' => $start->copy()->endOfMonth()->toDateString(),
            ],
            'previous' => [
                'year' => (int) $previous->year,
                'month' => (int) $previous->month,
                'label' => $previous->format('F Y'),
            ],
            'totals' => [
                'activities' => $activities->count(),
                'active' => $activities->filter(static fn (Activity $a): bool => $a->status === ActivityStatus::Active)->count(),
                'reached' => array_sum($thisMonth['reach']),
                'value' => array_sum($thisMonth['value']),
            ],
            'previous_totals' => [
                'reached' => array_sum($lastMonth['reach']),
                'value' => array_sum($lastMonth['value']),
            ],
            'projects' => $projects,
            'attention' => $attention,
            'ending_soon' => $endingSoon,
        ];
    }

    /* ------------------------------------------------------------------ internals */

    /**
     * Activities the scope may see.
     *
     * Drafts are excluded — a project that has not started has nothing to report and
     * would pad the table with empty rows. Archived ones are excluded too: the report
     * is about work in progress, and an archived activity's history is already in the
     * months it was actually running.
     *
     * @return Collection<int, Activity>
     */
    private function activitiesInScope(DashboardScope $scope): Collection
    {
        $query = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->whereNotIn('status', [ActivityStatus::Draft->value, ActivityStatus::Archived->value]);

        if ($scope->mdaIds !== null && ! $scope->isPartner()) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }
        if ($scope->programmeIds !== null) {
            $query->whereIn('programme_id', $scope->programmeIds);
        }

        return $query->get([
            'id', 'programme_id', 'owner_mda_id', 'name', 'status', 'involves_beneficiaries',
            'target_beneficiaries', 'budget_amount', 'starts_on', 'ends_on',
        ]);
    }

    /**
     * Reach and delivered value per activity for one period.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array{reach: array<string, int>, value: array<string, int>}
     */
    private function ledgerFor(?array $mdaIds, ?array $programmeIds, DashboardFilter $filter): array
    {
        $filters = $filter->ledgerFilters();

        $value = [];
        foreach ($this->ledger->scopedGroup('activity', $mdaIds, $programmeIds, $filters) as $group) {
            if ($group['key'] !== null) {
                $value[(string) $group['key']] = (int) $group['total_value'];
            }
        }

        return [
            'reach' => $this->ledger->scopedReachByActivity($mdaIds, $programmeIds, $filters),
            'value' => $value,
        ];
    }

    /**
     * Green / yellow / red from the CUMULATIVE completion rate, on the same thresholds
     * the dashboards already use — a project should not change colour depending on
     * which screen is describing it.
     */
    private function trafficLight(?float $completion): string
    {
        if ($completion === null) {
            return 'unrated';
        }

        $green = (float) config('reporting.programme_traffic_light.green_min', 0.8);
        $yellow = (float) config('reporting.programme_traffic_light.yellow_min', 0.5);

        return $completion >= $green ? 'green' : ($completion >= $yellow ? 'yellow' : 'red');
    }

    /**
     * What an officer should look at, in plain words.
     *
     * Each of these is a statement of fact rather than a judgement: "no delivery
     * recorded" is not the same as "nothing happened", and the wording keeps that
     * distinction because the report cannot tell the difference between a quiet month
     * and a month nobody entered.
     *
     * @return list<string>
     */
    private function concerns(
        Activity $activity,
        Carbon $start,
        int $monthValue,
        ?int $monthReached,
        ?float $completion,
        int $budget,
        int $spent,
    ): array {
        $out = [];
        $endOfMonth = $start->copy()->endOfMonth();

        if ($activity->ends_on !== null
            && $activity->ends_on->lt($endOfMonth)
            && $activity->status === ActivityStatus::Active) {
            $out[] = 'Timeline ended '.$activity->ends_on->format('j M Y').' but the activity is still open.';
        }

        if ($activity->status === ActivityStatus::Active && $monthValue === 0 && ($monthReached ?? 0) === 0) {
            $out[] = 'No delivery recorded this month.';
        }

        if ($completion !== null && $completion < (float) config('reporting.programme_traffic_light.yellow_min', 0.5)) {
            $out[] = 'Reached '.round($completion * 100).'% of its target to date.';
        }

        if ($budget > 0 && $spent > $budget) {
            $out[] = 'Recorded delivery value exceeds the budget set for it.';
        }

        return $out;
    }

    private function day(mixed $value): ?string
    {
        return $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : null;
    }
}
