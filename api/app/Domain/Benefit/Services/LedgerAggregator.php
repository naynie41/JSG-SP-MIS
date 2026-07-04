<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Illuminate\Database\Eloquent\Builder;

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
     * Allocated vs utilised for a programme (FR-PRG-04).
     *
     * @return array<string, mixed>
     */
    public function programmeBudget(Programme $programme): array
    {
        return $this->budget($programme->budget_amount, Benefit::query()->where('programme_id', $programme->id));
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
