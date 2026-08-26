<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Segments;

use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Support\CanonicalSchema;
use BackedEnum;
use Illuminate\Support\Str;

/**
 * The filter catalogue for the segment builder (FR-RPT-03), assembled from data rather
 * than hand-listed.
 *
 * Two sources, and the distinction is real:
 *
 *  - CANONICAL dimensions come from {@see CanonicalSchema::segmentableFields()} — the
 *    fields an MDA's file actually carries. Adding a segmentable field to the schema
 *    (disability, vulnerability tier, anything later) makes it a filter here with NO
 *    change to this class, the API, or the UI. That is the point: a schema field that
 *    is filterable in one report and missing from another is how a segment silently
 *    stops meaning what people think it means.
 *
 *  - SYSTEM dimensions are attributes SP-MIS stamps rather than receives: which
 *    programme or activity a person is enrolled in, where their record came from, when
 *    it was registered, its status, whether they sit in a household. They cannot come
 *    from the canonical schema because no source file supplies them.
 *
 * What is NOT here is as important. Identity fields — NIN, BVN, phone, name — are
 * excluded structurally by `segmentableFields()`, not by omission from a list somebody
 * has to remember to keep correct. A filter on an identifier is not a segment; it is a
 * lookup of one named person dressed as a report, and it would turn an aggregate-tier
 * user into someone who can confirm whether a specific individual is in the registry.
 */
final class SegmentDimensionRegistry
{
    /**
     * Attributes SP-MIS stamps on a record, which no source file provides.
     *
     * @var array<string, array<string, mixed>>
     */
    private const SYSTEM_DIMENSIONS = [
        'registration_source' => [
            'label' => 'Registration source',
            'kind' => SegmentDimension::KIND_ENUM,
            'column' => 'registration_source',
            'values' => RegistrationSource::class,
        ],
        'status' => [
            'label' => 'Status',
            'kind' => SegmentDimension::KIND_ENUM,
            'column' => 'status',
            'values' => BeneficiaryStatus::class,
        ],
        'registration_date' => [
            'label' => 'Registration date',
            'kind' => SegmentDimension::KIND_DATE,
            'column' => 'registration_date',
        ],
        'owner_mda' => [
            'label' => 'Owner MDA',
            'kind' => SegmentDimension::KIND_LOOKUP,
            'column' => 'owner_mda_id',
        ],
        // Relationship dimensions. The column is resolved by the query builder through
        // an enrollment subquery, not by a direct comparison — see SegmentQueryBuilder.
        'programme' => [
            'label' => 'Programme',
            'kind' => SegmentDimension::KIND_LOOKUP,
            'column' => 'enrollments.programme_id',
        ],
        'activity' => [
            'label' => 'Activity',
            'kind' => SegmentDimension::KIND_LOOKUP,
            'column' => 'enrollments.activity_id',
        ],
        'household' => [
            'label' => 'Household or individual',
            'kind' => SegmentDimension::KIND_ENUM,
            'column' => 'household_membership',
            'options' => [
                ['value' => 'household', 'label' => 'In a household'],
                ['value' => 'individual', 'label' => 'Individual (no household)'],
            ],
        ],
    ];

    /**
     * Filter keys resolved through a RELATIONSHIP (an enrollment or a household
     * membership) rather than a column on `beneficiaries`. They filter correctly; they
     * cannot be grouped by.
     *
     * @var list<string>
     */
    private const RELATIONAL = ['programme', 'activity', 'household', 'household_role'];

    /**
     * Every dimension the builder offers, keyed by filter key.
     *
     * @return array<string, SegmentDimension>
     */
    public function all(): array
    {
        $dimensions = [];

        foreach (CanonicalSchema::segmentableFields() as $field => $segment) {
            $dimensions[$field] = new SegmentDimension(
                key: $field,
                label: (string) ($segment['label'] ?? Str::headline($field)),
                kind: (string) $segment['kind'],
                column: $field,
                options: $this->optionsFrom($segment),
                canonical: true,
                unit: isset($segment['unit']) ? (string) $segment['unit'] : null,
                groupable: ! in_array($field, self::RELATIONAL, true),
            );
        }

        foreach (self::SYSTEM_DIMENSIONS as $key => $spec) {
            $dimensions[$key] = new SegmentDimension(
                key: $key,
                label: (string) $spec['label'],
                kind: (string) $spec['kind'],
                column: (string) $spec['column'],
                options: $this->optionsFrom($spec),
                canonical: false,
                groupable: ! in_array($key, self::RELATIONAL, true),
            );
        }

        return $dimensions;
    }

    public function get(string $key): ?SegmentDimension
    {
        return $this->all()[$key] ?? null;
    }

    /** @return list<string> */
    public function keys(): array
    {
        return array_keys($this->all());
    }

    /**
     * Enum options, from the declared backed enum or a literal list.
     *
     * @param  array<string, mixed>  $spec
     * @return list<array{value: string, label: string}>
     */
    private function optionsFrom(array $spec): array
    {
        if (isset($spec['options']) && is_array($spec['options'])) {
            /** @var list<array{value: string, label: string}> */
            return array_values($spec['options']);
        }

        $enum = $spec['values'] ?? null;
        if (! is_string($enum) || ! enum_exists($enum)) {
            return [];
        }

        $options = [];
        foreach ($enum::cases() as $case) {
            /** @var BackedEnum $case */
            $options[] = [
                'value' => (string) $case->value,
                'label' => Str::headline((string) $case->value),
            ];
        }

        return $options;
    }
}
