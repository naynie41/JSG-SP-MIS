<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Matching\Engine\MatchingEngine;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Per-row duplicate screening for an import batch (PRD FR-DUP-01, §8.1). Each row
 * is checked against BOTH the existing registry (index-backed blocking) and the
 * earlier rows in the same batch (in-memory, also blocked so it stays bounded).
 *
 * Stateful and single-use per batch: as each row is screened it is remembered so
 * later rows can match it. Read-only — it annotates, it never creates or blocks.
 */
class BatchDuplicateScreener
{
    /** In-memory block index of earlier rows: blockKey => list of records. */
    private array $index = [];

    public function __construct(
        private readonly FuzzyDuplicateFinder $registryFinder,
        private readonly MatchingEngine $engine,
        private readonly FieldNormalizer $normalizer,
    ) {}

    /**
     * Screen one row, then remember it for subsequent rows.
     *
     * @param  array<string, mixed>  $candidate  canonical fields
     * @return array{band: string, candidates: list<array<string, mixed>>}
     */
    public function screen(array $candidate, int $rowNumber, MatchingConfig $config): array
    {
        $matches = [];
        foreach ($this->registryFinder->find($candidate, $config) as $result) {
            $matches[] = $this->entry('registry', $result);
        }
        foreach ($this->matchWithinBatch($candidate, $config) as $result) {
            $matches[] = $this->entry('batch', $result);
        }

        $this->remember($candidate, $rowNumber);

        return ['band' => $this->highestBand($matches), 'candidates' => $matches];
    }

    /**
     * @param  array<string, mixed>  $candidate
     * @return list<MatchResult>
     */
    private function matchWithinBatch(array $candidate, MatchingConfig $config): array
    {
        $records = [];
        $seen = [];
        foreach ($this->blockKeys($candidate) as $key) {
            foreach ($this->index[$key] ?? [] as $record) {
                $id = $record['id'];
                if (! isset($seen[$id])) {
                    $seen[$id] = true;
                    $records[] = $record;
                }
            }
        }

        return array_values(array_filter(
            $this->engine->match($candidate, $records, $config),
            static fn (MatchResult $r) => $r->band !== MatchBand::None,
        ));
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function remember(array $candidate, int $rowNumber): void
    {
        $record = ['id' => (string) $rowNumber] + $candidate;
        foreach ($this->blockKeys($candidate) as $key) {
            $this->index[$key][] = $record;
        }
    }

    /**
     * @param  array<string, mixed>  $candidate
     * @return list<string>
     */
    private function blockKeys(array $candidate): array
    {
        $keys = [];
        foreach (['nin', 'bvn', 'phone'] as $field) {
            $value = $this->normalizer->normalize($field, $candidate[$field] ?? null);
            if ($value !== null) {
                $keys[] = $field.':'.$value;
            }
        }

        $nameDob = Beneficiary::blockNameDobFor(
            isset($candidate['last_name']) ? (string) $candidate['last_name'] : null,
            isset($candidate['date_of_birth']) ? (string) $candidate['date_of_birth'] : null,
        );
        if ($nameDob !== null) {
            $keys[] = 'nd:'.$nameDob;
        }

        return $keys;
    }

    /**
     * @return array<string, mixed>
     */
    private function entry(string $type, MatchResult $result): array
    {
        return [
            'type' => $type,
            'reference' => $result->reference,
            'band' => $result->band->value,
            'score' => round($result->score->composite, 4),
            'matched_fields' => $result->score->matchedFields(),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $matches
     */
    private function highestBand(array $matches): string
    {
        $bands = array_column($matches, 'band');
        if (in_array(MatchBand::Exact->value, $bands, true)) {
            return MatchBand::Exact->value;
        }
        if (in_array(MatchBand::Probable->value, $bands, true)) {
            return MatchBand::Probable->value;
        }

        return MatchBand::None->value;
    }
}
