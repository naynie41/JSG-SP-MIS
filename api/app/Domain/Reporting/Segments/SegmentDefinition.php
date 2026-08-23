<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

use App\Domain\Reporting\Exceptions\InvalidReportDefinitionException;

/**
 * A composed segment query (FR-RPT-03): a set of filters, plus an optional breakdown
 * dimension for the chart.
 *
 * Composition is AND across dimensions, OR within one — "female AND aged 20-25 AND in
 * Dutse", where a multi-select of LGAs means any of them. That is the shape people
 * actually reason in, and it is also the only shape that cannot widen a scope: every
 * clause added can only remove rows.
 *
 * The definition is persisted with the export run, so an auditor can reconstruct
 * exactly which population was pulled, by whom, under which scope.
 */
final readonly class SegmentDefinition
{
    /**
     * @param  array<string, array{op: string, values: list<string>}>  $filters
     * @param  string|null  $breakdown  dimension key for the chart, null for none
     */
    public function __construct(
        public array $filters = [],
        public ?string $breakdown = null,
    ) {}

    /**
     * Parse and validate a client payload against the dimension catalogue.
     *
     * Anything not in the catalogue is REFUSED rather than ignored. A silently dropped
     * filter is the dangerous failure here: the caller believes they narrowed the
     * population and exports a wider one, which on an aggregate tier is exactly the
     * mistake the cell-size guard exists to prevent.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload, SegmentDimensionRegistry $registry): self
    {
        $filters = [];

        /** @var array<string, mixed> $raw */
        $raw = is_array($payload['filters'] ?? null) ? $payload['filters'] : [];

        foreach ($raw as $key => $spec) {
            $dimension = $registry->get((string) $key);
            if ($dimension === null) {
                throw new InvalidReportDefinitionException("Unknown filter: {$key}.");
            }
            if (! is_array($spec)) {
                throw new InvalidReportDefinitionException("Filter {$key} must be an object.");
            }

            $values = array_values(array_filter(
                array_map(static fn (mixed $v): string => trim((string) $v), (array) ($spec['values'] ?? [])),
                static fn (string $v): bool => $v !== '',
            ));

            if ($values === []) {
                continue; // an empty selection is "no filter", not an error
            }

            $op = self::operatorFor($dimension, (string) ($spec['op'] ?? ''));

            if ($op === 'between' && count($values) !== 2) {
                throw new InvalidReportDefinitionException("Filter {$key} needs exactly two values (from, to).");
            }
            if ($op === 'in') {
                self::assertAllowedValues($dimension, $values);
            }

            $filters[$dimension->key] = ['op' => $op, 'values' => $values];
        }

        $breakdown = isset($payload['breakdown']) ? (string) $payload['breakdown'] : null;

        if ($breakdown !== null && $registry->get($breakdown) === null) {
            throw new InvalidReportDefinitionException("Unknown breakdown: {$breakdown}.");
        }

        return new self($filters, $breakdown);
    }

    /** Range kinds take a two-ended range; everything else is a multi-select. */
    private static function operatorFor(SegmentDimension $dimension, string $requested): string
    {
        $natural = in_array($dimension->kind, [SegmentDimension::KIND_AGE, SegmentDimension::KIND_DATE], true)
            ? 'between'
            : 'in';

        if ($requested !== '' && $requested !== $natural) {
            throw new InvalidReportDefinitionException(
                "Filter {$dimension->key} does not support the “{$requested}” operator."
            );
        }

        return $natural;
    }

    /**
     * An enum dimension accepts only its declared values. Without this a caller could
     * pass an arbitrary string and get a silently empty segment, which reads as "nobody
     * matches" rather than "you asked for something that does not exist".
     *
     * @param  list<string>  $values
     */
    private static function assertAllowedValues(SegmentDimension $dimension, array $values): void
    {
        if ($dimension->kind !== SegmentDimension::KIND_ENUM || $dimension->options === []) {
            return; // lookups are open by nature (wards, programme ids)
        }

        $allowed = array_column($dimension->options, 'value');
        foreach ($values as $value) {
            if (! in_array($value, $allowed, true)) {
                throw new InvalidReportDefinitionException("“{$value}” is not a valid {$dimension->label}.");
            }
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'filters' => $this->filters,
            'breakdown' => $this->breakdown,
        ], static fn (mixed $value): bool => $value !== null && $value !== []);
    }

    /** A short human summary for the run label and the audit trail. */
    public function label(): string
    {
        if ($this->filters === []) {
            return 'Segment: all beneficiaries in scope';
        }

        $parts = [];
        foreach ($this->filters as $key => $filter) {
            $parts[] = $key.' '.($filter['op'] === 'between'
                ? implode('–', $filter['values'])
                : implode('/', $filter['values']));
        }

        return 'Segment: '.implode(', ', $parts);
    }
}
