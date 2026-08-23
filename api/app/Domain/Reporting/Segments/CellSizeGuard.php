<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

/**
 * Small-cell suppression for aggregate output (NFR-PRV-01, NDPA/NDPR).
 *
 * A count is not automatically anonymous. "Female, aged 20–25, Dutse ward 3: 1" names
 * a person to anyone who knows the neighbourhood, and a filter builder makes that
 * trivially reachable — narrow until the number is small, and the aggregate has become
 * a disclosure. The standard defence in official statistics is a minimum cell size:
 * below N, publish nothing for that group.
 *
 * The threshold is CONFIGURABLE (`reporting.min_cell_size`) because it is a
 * stakeholder decision, not an engineering one — different data-protection postures
 * pick different N — and CLAUDE.md §8 forbids hard-coding those.
 *
 * Suppressed groups are not silently dropped. They are kept, with the count replaced by
 * a marker and rolled into a "suppressed" total, because a vanishing row is itself a
 * signal: a reader comparing two runs would learn that a group exists and is small.
 * Saying "withheld" says less than an absence does.
 */
class CellSizeGuard
{
    public const SUPPRESSED = '—';

    public const DEFAULT_MINIMUM = 5;

    public function minimum(): int
    {
        return max(0, (int) config('reporting.min_cell_size', self::DEFAULT_MINIMUM));
    }

    /**
     * Apply suppression to a breakdown.
     *
     * @param  list<array{key: string, label: string, count: int}>  $groups
     * @return array{
     *     groups: list<array{key: string, label: string, count: int|null, suppressed: bool}>,
     *     suppressed_groups: int,
     *     suppressed_total: int,
     *     minimum: int,
     * }
     */
    public function apply(array $groups, bool $enabled): array
    {
        $minimum = $this->minimum();
        $out = [];
        $suppressedGroups = 0;
        $suppressedTotal = 0;

        foreach ($groups as $group) {
            $small = $enabled && $minimum > 0 && $group['count'] > 0 && $group['count'] < $minimum;

            if ($small) {
                $suppressedGroups++;
                $suppressedTotal += $group['count'];
            }

            $out[] = [
                'key' => $group['key'],
                'label' => $group['label'],
                'count' => $small ? null : $group['count'],
                'suppressed' => $small,
            ];
        }

        return [
            'groups' => $out,
            'suppressed_groups' => $suppressedGroups,
            'suppressed_total' => $suppressedTotal,
            'minimum' => $minimum,
        ];
    }

    /**
     * Whether a whole-segment total may be shown.
     *
     * The grand total gets the same treatment as a group. Without this, a caller could
     * narrow the filters until one person matched and read the answer straight off the
     * total — every suppressed breakdown underneath it notwithstanding.
     */
    public function totalIsSuppressed(int $total, bool $enabled): bool
    {
        $minimum = $this->minimum();

        return $enabled && $minimum > 0 && $total > 0 && $total < $minimum;
    }
}
