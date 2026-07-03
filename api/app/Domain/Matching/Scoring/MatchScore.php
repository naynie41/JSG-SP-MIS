<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring;

/**
 * The result of scoring one candidate against one existing record. Carries the
 * normalised composite score, whether a deterministic key set matched, and a
 * PII-free, per-rule explanation (field names + similarities only — never the
 * raw values) for transparency and audit (PRD FR-DUP-03).
 */
final readonly class MatchScore
{
    /**
     * @param  list<array<string, mixed>>  $explanation  ordered rules that were evaluated
     */
    public function __construct(
        public float $composite,
        public bool $deterministic,
        public array $explanation,
    ) {}

    /**
     * @return array{composite: float, deterministic: bool, explanation: list<array<string, mixed>>}
     */
    public function toArray(): array
    {
        return [
            'composite' => round($this->composite, 4),
            'deterministic' => $this->deterministic,
            'explanation' => $this->explanation,
        ];
    }

    /**
     * The field names that drove the match (deterministic keys + strong fuzzy
     * fields) — for transparency; never the raw values. Shared by the batch
     * screener and the ad-hoc serve search so both explain matches identically.
     *
     * @return list<string>
     */
    public function matchedFields(): array
    {
        $fields = [];
        foreach ($this->explanation as $entry) {
            if (($entry['type'] ?? null) === 'deterministic' && ($entry['matched'] ?? false)) {
                foreach ($entry['fields'] as $field) {
                    $fields[] = $field;
                }
            } elseif (($entry['type'] ?? null) === 'fuzzy' && ($entry['similarity'] ?? 0.0) >= 0.85) {
                $fields[] = $entry['field'];
            }
        }

        return array_values(array_unique($fields));
    }
}
