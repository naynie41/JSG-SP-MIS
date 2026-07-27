<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The single aggregation seam over the benefit ledger (PRD FR-BEN-03, FR-PRG-04).
 * Every dashboard/report figure goes through here so the backing can later be
 * swapped for materialised rollups without touching callers.
 *
 * All queries run through the scoped {@see Benefit} model, so they automatically
 * respect MDA scoping/visibility (a caller sees their own deliveries; oversight
 * sees all). Reversed entries are excluded from "utilised"/totals — they are voided
 * deliveries, not delivered value.
 */
class LedgerAggregator
{
    /** Whitelisted grouping dimensions → ledger column (guards raw SQL). */
    private const DIMENSIONS = [
        'programme' => 'programme_id',
        'activity' => 'activity_id',
        'mda' => 'mda_id',
        'lga' => 'lga',
        'ward' => 'ward',
        'beneficiary' => 'beneficiary_id',
        'benefit_type' => 'benefit_type',
    ];

    /**
     * Allocated vs utilised for a programme (FR-PRG-04). Budget lives on the
     * activities that run the (global) programme (§10), so allocated = the sum of
     * those activities' budgets across every MDA running it.
     *
     * @return array<string, mixed>
     */
    public function programmeBudget(Programme $programme): array
    {
        $allocated = (int) Activity::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('programme_id', $programme->id)
            ->sum('budget_amount');

        return $this->budget($allocated, Benefit::query()->where('programme_id', $programme->id));
    }

    /**
     * Allocated vs utilised for an activity (FR-PRG-04).
     *
     * @return array<string, mixed>
     */
    public function activityBudget(Activity $activity): array
    {
        return $this->budget($activity->budget_amount, Benefit::query()->where('activity_id', $activity->id));
    }

    /**
     * Group the ledger by a dimension with per-group and grand totals (FR-BEN-03).
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function aggregate(string $dimension, array $filters): array
    {
        $column = self::DIMENSIONS[$dimension] ?? throw new \InvalidArgumentException("Unknown dimension: {$dimension}");

        $base = $this->applyFilters(Benefit::query(), $filters);

        $groups = (clone $base)
            ->selectRaw("{$column} as group_key, count(*) as cnt, coalesce(sum(monetary_value), 0) as val, coalesce(sum(quantity), 0) as qty")
            ->groupBy($column)
            ->get()
            ->map(fn (Benefit $row) => [
                'key' => $row->getAttribute('group_key'),
                'benefit_count' => (int) $row->getAttribute('cnt'),
                'total_value' => (int) $row->getAttribute('val'),
                'total_quantity' => (string) $row->getAttribute('qty'),
            ])
            ->all();

        return [
            'group_by' => $dimension,
            'groups' => $groups,
            'totals' => $this->totals(clone $base),
        ];
    }

    /**
     * Ledger totals for an explicit dashboard scope (PRD FR-RPT-01/02), bypassing the
     * request-time MdaScope so it also runs on the scheduler/queue. Reversed entries
     * are excluded (voided deliveries). `$programmeIds` (partner scope) takes
     * precedence; otherwise `$mdaIds` constrains by delivering MDA; both null =
     * state-wide. An empty array constrains to nothing (deny by default).
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array{benefit_count: int, total_value: int, total_quantity: string}
     */
    public function scopedTotals(?array $mdaIds, ?array $programmeIds, array $filters = []): array
    {
        return $this->totals($this->scopedLedger($mdaIds, $programmeIds, $filters));
    }

    /**
     * Allocated-vs-utilised for a dashboard scope (FR-PRG-04): allocated = the sum of
     * the scoped activities' budgets (budget lives on activities, §10); utilised =
     * delivered value in scope. Partner scope constrains by funded programme;
     * otherwise by the delivering/owning MDA.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array<string, mixed>
     */
    public function scopedBudget(?array $mdaIds, ?array $programmeIds, array $filters = []): array
    {
        $activities = Activity::query()->withoutGlobalScope(MdaScope::class);
        if ($programmeIds !== null) {
            $activities->whereIn('programme_id', $programmeIds);
        }
        if ($mdaIds !== null) {
            $activities->whereIn('owner_mda_id', $mdaIds);
        }
        // Filter the allocation the same way (owner MDA + programme + area), so budget
        // vs actual stays coherent under a filter. Date bounds apply to deliveries, not
        // to a programme's allocation, so they are not applied here.
        if (! empty($filters['programme_id'])) {
            $activities->where('programme_id', $filters['programme_id']);
        }
        if (! empty($filters['mda_id'])) {
            $activities->where('owner_mda_id', $filters['mda_id']);
        }
        if (! empty($filters['lga'])) {
            $activities->where('lga', $filters['lga']);
        }
        if (! empty($filters['ward'])) {
            $activities->where('ward', $filters['ward']);
        }

        $allocated = (int) $activities->sum('budget_amount');
        $totals = $this->scopedTotals($mdaIds, $programmeIds, $filters);
        $utilised = $totals['total_value'];

        return [
            'allocated' => $allocated,
            'utilized_value' => $utilised,
            'utilized_quantity' => $totals['total_quantity'],
            'benefit_count' => $totals['benefit_count'],
            'remaining' => $allocated - $utilised,
            'utilization_rate' => $allocated > 0 ? round($utilised / $allocated, 4) : null,
        ];
    }

    /**
     * Group the scoped ledger by a whitelisted dimension (FR-BEN-03), for a dashboard
     * scope. Returns `[key => ['benefit_count','total_value','total_quantity']]`.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array<int, array<string, mixed>>
     */
    public function scopedGroup(string $dimension, ?array $mdaIds, ?array $programmeIds, array $filters = []): array
    {
        $column = self::DIMENSIONS[$dimension] ?? throw new \InvalidArgumentException("Unknown dimension: {$dimension}");

        return $this->scopedLedger($mdaIds, $programmeIds, $filters)
            ->selectRaw("{$column} as group_key, count(*) as cnt, coalesce(sum(monetary_value), 0) as val, coalesce(sum(quantity), 0) as qty")
            ->groupBy($column)
            ->get()
            ->map(fn (Benefit $row) => [
                'key' => $row->getAttribute('group_key'),
                'benefit_count' => (int) $row->getAttribute('cnt'),
                'total_value' => (int) $row->getAttribute('val'),
                'total_quantity' => (string) $row->getAttribute('qty'),
            ])
            ->all();
    }

    /**
     * NET-UNIQUE beneficiaries SERVED in scope — distinct beneficiaries with at least
     * one (non-reversed) delivery. This is deliberately distinct from the GROSS
     * delivery count ({@see scopedTotals}['benefit_count']): a person served three
     * times counts once here, three times there.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     */
    public function scopedDistinctBeneficiaries(?array $mdaIds, ?array $programmeIds, array $filters = []): int
    {
        return (int) $this->scopedLedger($mdaIds, $programmeIds, $filters)->distinct()->count('beneficiary_id');
    }

    /**
     * Net-unique beneficiaries reached per programme (distinct served), for the
     * programme-performance metric.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array<string, int>
     */
    public function scopedReachByProgramme(?array $mdaIds, ?array $programmeIds, array $filters = []): array
    {
        return $this->scopedLedger($mdaIds, $programmeIds, $filters)
            ->selectRaw('programme_id, count(distinct beneficiary_id) as reached')
            ->groupBy('programme_id')
            ->get()
            ->mapWithKeys(fn (Benefit $r) => [(string) $r->getAttribute('programme_id') => (int) $r->getAttribute('reached')])
            ->all();
    }

    /**
     * Net-unique beneficiaries reached per ACTIVITY (distinct served), for the
     * activity-level drill-down under programme performance.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array<string, int>
     */
    public function scopedReachByActivity(?array $mdaIds, ?array $programmeIds, array $filters = []): array
    {
        return $this->scopedLedger($mdaIds, $programmeIds, $filters)
            ->selectRaw('activity_id, count(distinct beneficiary_id) as reached')
            ->groupBy('activity_id')
            ->get()
            ->mapWithKeys(fn (Benefit $r) => [(string) $r->getAttribute('activity_id') => (int) $r->getAttribute('reached')])
            ->all();
    }

    /**
     * Monthly disbursed value for the last `$months` months (periodised trend). Keys
     * are 'YYYY-MM'; only months with deliveries appear (callers zero-fill the gaps).
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array<string, int>
     */
    public function scopedDisbursementSeries(?array $mdaIds, ?array $programmeIds, int $months, array $filters = []): array
    {
        $expr = self::monthKeyExpr('delivery_date');
        $since = Carbon::now()->startOfMonth()->subMonths(max(0, $months - 1))->toDateString();

        return $this->scopedLedger($mdaIds, $programmeIds, $filters)
            ->whereDate('delivery_date', '>=', $since)
            ->selectRaw("{$expr} as m, coalesce(sum(monetary_value), 0) as v")
            ->groupByRaw($expr)
            ->get()
            ->mapWithKeys(fn (Benefit $r) => [(string) $r->getAttribute('m') => (int) $r->getAttribute('v')])
            ->all();
    }

    /**
     * Net-unique beneficiaries SERVED per admin area (LGA/Ward), for the GIS map's
     * click-through detail. Keyed by the raw area value ('' when unset). Whitelisted
     * column guards the raw SQL.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @return array<string, int>
     */
    public function scopedDistinctByArea(string $areaColumn, ?array $mdaIds, ?array $programmeIds, array $filters = []): array
    {
        $col = in_array($areaColumn, ['lga', 'ward'], true)
            ? $areaColumn
            : throw new \InvalidArgumentException("Unknown area column: {$areaColumn}");

        return $this->scopedLedger($mdaIds, $programmeIds, $filters)
            ->selectRaw("{$col} as k, count(distinct beneficiary_id) as c")
            ->groupBy($col)
            ->get()
            ->mapWithKeys(fn (Benefit $r) => [(string) ($r->getAttribute('k') ?? '') => (int) $r->getAttribute('c')])
            ->all();
    }

    /** Driver-aware 'YYYY-MM' month bucket for a date column (sqlite + pgsql). */
    public static function monthKeyExpr(string $column): string
    {
        return DB::connection()->getDriverName() === 'pgsql'
            ? "to_char({$column}, 'YYYY-MM')"
            : "strftime('%Y-%m', {$column})";
    }

    /**
     * A ledger query constrained to a dashboard scope, reversed entries excluded. The
     * scope axes are applied INDEPENDENTLY (both when both are set), so a programme
     * filter on an MDA scope still stays inside that MDA. `$filters` (programme_id,
     * mda_id, lga, ward, date_from/date_to on delivery_date) narrow further — they can
     * only ever intersect the scope, never widen it.
     *
     * @param  list<string>|null  $mdaIds
     * @param  list<string>|null  $programmeIds
     * @param  array<string, string>  $filters
     * @return Builder<Benefit>
     */
    private function scopedLedger(?array $mdaIds, ?array $programmeIds, array $filters = []): Builder
    {
        $query = Benefit::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('status', '!=', BenefitStatus::Reversed->value);

        if ($programmeIds !== null) {
            $query->whereIn('programme_id', $programmeIds);
        }
        if ($mdaIds !== null) {
            $query->whereIn('mda_id', $mdaIds);
        }

        foreach (['programme_id', 'mda_id', 'lga', 'ward'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('delivery_date', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('delivery_date', '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * @param  Builder<Benefit>  $utilisedQuery
     * @return array<string, mixed>
     */
    private function budget(?int $allocated, Builder $utilisedQuery): array
    {
        $totals = $this->totals($utilisedQuery->where('status', '!=', BenefitStatus::Reversed->value));
        $utilised = $totals['total_value'];

        return [
            'allocated' => $allocated,
            'utilized_value' => $utilised,
            'utilized_quantity' => $totals['total_quantity'],
            'benefit_count' => $totals['benefit_count'],
            'remaining' => $allocated !== null ? $allocated - $utilised : null,
            'utilization_rate' => $allocated !== null && $allocated > 0 ? round($utilised / $allocated, 4) : null,
        ];
    }

    /**
     * @param  Builder<Benefit>  $query
     * @return array{benefit_count: int, total_value: int, total_quantity: string}
     */
    private function totals(Builder $query): array
    {
        $row = $query->selectRaw('count(*) as cnt, coalesce(sum(monetary_value), 0) as val, coalesce(sum(quantity), 0) as qty')->first();

        return [
            'benefit_count' => (int) ($row?->getAttribute('cnt') ?? 0),
            'total_value' => (int) ($row?->getAttribute('val') ?? 0),
            'total_quantity' => (string) ($row?->getAttribute('qty') ?? '0'),
        ];
    }

    /**
     * @param  Builder<Benefit>  $query
     * @param  array<string, mixed>  $filters
     * @return Builder<Benefit>
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        foreach (['programme_id', 'activity_id', 'mda_id', 'benefit_type', 'lga', 'ward'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('delivery_date', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('delivery_date', '<=', $filters['date_to']);
        }
        // Explicit status filter overrides the default (exclude voided/reversed).
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        } else {
            $query->where('status', '!=', BenefitStatus::Reversed->value);
        }

        return $query;
    }
}
