<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Reports\AdHoc;

/**
 * A user-composed ad-hoc report spec (PRD FR-RPT-03): a dataset, the dimensions to
 * group by, the measures to aggregate, and narrowing filters. It carries only KEYS —
 * every key is validated against the dataset whitelist before use, so a definition
 * can never name a raw/PII column or an unlisted filter. Scope is NOT part of the
 * definition; it is resolved from the running user each time it is built.
 */
final class AdHocDefinition
{
    /**
     * @param  list<string>  $groupBy
     * @param  list<string>  $measures
     * @param  array<string, string>  $filters
     */
    public function __construct(
        public readonly string $dataset,
        public readonly array $groupBy,
        public readonly array $measures,
        public readonly array $filters,
        public readonly ?string $name = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            dataset: (string) ($data['dataset'] ?? ''),
            groupBy: array_values(array_map('strval', (array) ($data['group_by'] ?? []))),
            measures: array_values(array_map('strval', (array) ($data['measures'] ?? []))),
            filters: array_map('strval', array_filter(
                (array) ($data['filters'] ?? []),
                fn ($value): bool => $value !== null && $value !== '',
            )),
            name: isset($data['name']) ? (string) $data['name'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'dataset' => $this->dataset,
            'group_by' => $this->groupBy,
            'measures' => $this->measures,
            'filters' => $this->filters,
            'name' => $this->name,
        ];
    }

    public function label(): string
    {
        return $this->name ?? 'Ad-hoc report';
    }
}
