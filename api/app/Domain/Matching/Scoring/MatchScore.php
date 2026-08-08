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

    /** Similarity at or above which a fuzzy field reads as the same value. */
    private const EXACT_AT = 0.999;

    /** Similarity at or above which a fuzzy field reads as a near miss. */
    private const NEAR_AT = 0.85;

    /**
     * Per-field verdicts for the human adjudication screen (FR-DUP-09).
     *
     * The officer answering "is this the same person?" needs to see WHICH fields
     * agreed and which did not. They cannot be shown the existing record's
     * values — MatchReveal withholds NIN/BVN/phone/DOB precisely because the
     * record belongs to another MDA (FR-DUP-04) — so the comparison is expressed
     * as verdicts computed here, server-side. Nothing in the returned structure
     * carries a field value; it is field names, booleans and similarities only.
     *
     * A field that matched a deterministic key set is reported `exact`
     * regardless of its fuzzy similarity: that is what made it a definitive
     * duplicate.
     *
     * @return list<array{field: string, verdict: string, similarity: float|null, weight: float|null, participated: bool, deterministic: bool}>
     */
    public function fieldComparisons(): array
    {
        $deterministicFields = [];
        foreach ($this->explanation as $entry) {
            if (($entry['type'] ?? null) !== 'deterministic' || ! ($entry['matched'] ?? false)) {
                continue;
            }
            foreach ($entry['fields'] as $field) {
                $deterministicFields[(string) $field] = true;
            }
        }

        $comparisons = [];
        $seen = [];

        foreach ($this->explanation as $entry) {
            if (($entry['type'] ?? null) !== 'fuzzy') {
                continue;
            }
            $field = (string) $entry['field'];
            $seen[$field] = true;
            $isDeterministic = isset($deterministicFields[$field]);

            $comparisons[] = [
                'field' => $field,
                'verdict' => $isDeterministic
                    ? 'exact'
                    : self::verdictFor($entry),
                'similarity' => isset($entry['similarity']) ? (float) $entry['similarity'] : null,
                'weight' => isset($entry['weight']) ? (float) $entry['weight'] : null,
                'participated' => true,
                'deterministic' => $isDeterministic,
            ];
        }

        // A deterministic key (typically NIN or BVN) need not appear among the
        // fuzzy rules at all, yet it is the single most important thing to show.
        foreach (array_keys($deterministicFields) as $field) {
            if (isset($seen[$field])) {
                continue;
            }
            array_unshift($comparisons, [
                'field' => $field,
                'verdict' => 'exact',
                'similarity' => 1.0,
                'weight' => null,
                'participated' => true,
                'deterministic' => true,
            ]);
        }

        return $comparisons;
    }

    /**
     * @param  array<string, mixed>  $entry
     */
    private static function verdictFor(array $entry): string
    {
        $incoming = (bool) ($entry['incoming_present'] ?? ($entry['present'] ?? false));
        $existing = (bool) ($entry['existing_present'] ?? ($entry['present'] ?? false));

        if (! $incoming && ! $existing) {
            return 'absent_both';
        }
        if (! $incoming) {
            return 'absent_incoming';
        }
        if (! $existing) {
            return 'absent_existing';
        }

        $similarity = (float) ($entry['similarity'] ?? 0.0);

        return match (true) {
            $similarity >= self::EXACT_AT => 'exact',
            $similarity >= self::NEAR_AT => 'near',
            default => 'differs',
        };
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
