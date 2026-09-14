<?php

declare(strict_types=1);

namespace App\Domain\Reporting\DuplicateReview;

use App\Domain\Matching\Enums\MatchBand;

/**
 * What the duplicate review report is narrowed to: when the matches were found, and
 * which band. Persisted on a queued run as {@see self::toArray()}, so the file is built
 * from exactly the narrowing the officer was looking at.
 */
final readonly class DuplicateReviewFilter
{
    public function __construct(
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?string $band = null,
    ) {}

    /**
     * Tolerant by design: a run's stored params are re-read here long after validation,
     * so anything malformed is dropped to "no narrowing" rather than failing the job.
     *
     * @param  array<string, mixed>  $input
     */
    public static function fromArray(array $input): self
    {
        $band = $input['band'] ?? null;

        return new self(
            dateFrom: self::date($input['date_from'] ?? null),
            dateTo: self::date($input['date_to'] ?? null),
            band: in_array($band, self::bands(), true) ? $band : null,
        );
    }

    /**
     * The bands a person reviews. `none` is not a match, so it is never a filter value.
     *
     * @return list<string>
     */
    public static function bands(): array
    {
        return [MatchBand::Exact->value, MatchBand::Probable->value];
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return array_filter([
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'band' => $this->band,
        ], static fn (?string $value): bool => $value !== null);
    }

    /** The narrowing in words, for a file's subtitle. */
    public function label(): string
    {
        $band = match ($this->band) {
            MatchBand::Exact->value => 'Exact matches',
            MatchBand::Probable->value => 'Probable matches',
            default => 'All matches',
        };

        $period = match (true) {
            $this->dateFrom !== null && $this->dateTo !== null => "found {$this->dateFrom} to {$this->dateTo}",
            $this->dateFrom !== null => "found from {$this->dateFrom}",
            $this->dateTo !== null => "found up to {$this->dateTo}",
            default => 'all dates',
        };

        return "{$band}, {$period}";
    }

    private static function date(mixed $value): ?string
    {
        return is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 ? $value : null;
    }
}
