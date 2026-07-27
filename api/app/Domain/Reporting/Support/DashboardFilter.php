<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Support;

use App\Domain\Benefit\Services\LedgerAggregator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * A user-chosen NARROWING of the executive dashboard (cross-cutting filters, Phase 6E):
 * a period (year / quarter / month), a programme, an area (LGA / ward), and an MDA.
 *
 * A filter can only ever narrow what a {@see DashboardScope} already permits — it is
 * applied ON TOP of the scope in every query, so a caller who passes an MDA/programme
 * outside their scope simply gets the (empty) intersection. Enforcement is therefore
 * structural: filters never widen visibility.
 *
 * Filters change the aggregates, so a filtered request cannot be served from the
 * unfiltered snapshot; the reporting layer recomputes live when {@see isEmpty()} is
 * false, and stays on the fast snapshot path when it is true.
 */
final class DashboardFilter
{
    public function __construct(
        public readonly ?int $year = null,
        public readonly ?int $quarter = null,   // 1–4
        public readonly ?int $month = null,     // 1–12
        public readonly ?string $programmeId = null,
        public readonly ?string $lga = null,
        public readonly ?string $ward = null,
        public readonly ?string $mdaId = null,
    ) {}

    public static function none(): self
    {
        return new self;
    }

    public static function fromRequest(Request $request): self
    {
        $v = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'quarter' => ['nullable', 'integer', 'min:1', 'max:4'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'programme_id' => ['nullable', 'uuid'],
            'lga' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
            'mda_id' => ['nullable', 'uuid'],
        ]);

        return new self(
            year: $v['year'] ?? null,
            quarter: $v['quarter'] ?? null,
            month: $v['month'] ?? null,
            programmeId: $v['programme_id'] ?? null,
            lga: $v['lga'] ?? null,
            ward: $v['ward'] ?? null,
            mdaId: $v['mda_id'] ?? null,
        );
    }

    public function isEmpty(): bool
    {
        return $this->year === null && $this->quarter === null && $this->month === null
            && $this->programmeId === null && $this->lga === null && $this->ward === null && $this->mdaId === null;
    }

    /**
     * Inclusive [from, to] date bounds derived from year/quarter/month, or [null, null]
     * when no period is set. A quarter/month without a year assumes the current year.
     *
     * @return array{0: ?string, 1: ?string}
     */
    public function dateRange(): array
    {
        if ($this->year === null && $this->quarter === null && $this->month === null) {
            return [null, null];
        }

        $year = $this->year ?? (int) Carbon::now()->year;

        if ($this->month !== null) {
            $start = Carbon::create($year, $this->month, 1);

            return [$start->toDateString(), $start->copy()->endOfMonth()->toDateString()];
        }

        if ($this->quarter !== null) {
            $start = Carbon::create($year, ($this->quarter - 1) * 3 + 1, 1);

            return [$start->toDateString(), $start->copy()->addMonths(2)->endOfMonth()->toDateString()];
        }

        $start = Carbon::create($year, 1, 1);

        return [$start->toDateString(), $start->copy()->endOfYear()->toDateString()];
    }

    /**
     * Ledger query-filter extras (applied to `delivery_date` + programme/mda/area),
     * only the set keys — for the scoped {@see LedgerAggregator}.
     *
     * @return array<string, string>
     */
    public function ledgerFilters(): array
    {
        [$from, $to] = $this->dateRange();

        return array_filter([
            'programme_id' => $this->programmeId,
            'mda_id' => $this->mdaId,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'date_from' => $from,
            'date_to' => $to,
        ], fn ($v) => $v !== null);
    }

    /**
     * The echo returned to the client (so the UI can reflect the active filter).
     *
     * @return array<string, int|string|null>
     */
    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'quarter' => $this->quarter,
            'month' => $this->month,
            'programme_id' => $this->programmeId,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'mda_id' => $this->mdaId,
        ];
    }
}
