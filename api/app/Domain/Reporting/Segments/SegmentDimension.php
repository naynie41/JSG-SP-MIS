<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

/**
 * One dimension a report may be segmented by (FR-RPT-03).
 *
 * A dimension is a way to describe a GROUP. It carries the column it filters, how the
 * filter is shaped, and — for enums — the allowed values, so the API can publish a
 * complete filter catalogue without the client knowing anything about the schema.
 */
final readonly class SegmentDimension
{
    public const KIND_ENUM = 'enum';

    public const KIND_LOOKUP = 'lookup';

    public const KIND_AGE = 'age';

    public const KIND_DATE = 'date';

    /**
     * @param  string  $key  the filter key clients send
     * @param  string  $column  the beneficiaries column it resolves to
     * @param  list<array{value: string, label: string}>  $options  for enum kinds
     * @param  bool  $canonical  true when it came from the canonical import schema
     *                           (DM.1) rather than being a system-stamped attribute
     */
    public function __construct(
        public string $key,
        public string $label,
        public string $kind,
        public string $column,
        public array $options = [],
        public bool $canonical = true,
        public ?string $unit = null,
        /**
         * Whether the chart can group by this dimension.
         *
         * False for anything resolved through a RELATIONSHIP — programme, activity,
         * household role. Those filter the population correctly but have no column on
         * `beneficiaries` to group by, and joining to get one would make the chart count
         * enrollments or memberships while the table counts people.
         */
        public bool $groupable = true,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $payload = array_filter([
            'key' => $this->key,
            'label' => $this->label,
            'kind' => $this->kind,
            'canonical' => $this->canonical,
            'unit' => $this->unit,
            'groupable' => $this->groupable,
            'options' => $this->options === [] ? null : $this->options,
        ], static fn (mixed $value): bool => $value !== null);

        // Re-set after the filter: `false` is meaningful here, not absence.
        $payload['groupable'] = $this->groupable;

        return $payload;
    }
}
