<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Matching\Engine\DuplicateCascade;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Per-row duplicate screening for an import batch (PRD FR-DUP-01, §8.1). Each row
 * is checked against BOTH the existing registry (index-backed blocking) and the
 * earlier rows in the same batch (in-memory, also blocked so it stays bounded).
 *
 * The ordered cascade (§9) decides the outcome: exact NIN → exact BVN → fuzzy,
 * stopping at the first exact stage — so an exact-identifier match is reported
 * alone, never alongside fuzzy noise. Stateful and single-use per batch: as each
 * row is screened it is remembered so later rows can match it. Read-only.
 */
class BatchDuplicateScreener
{
    /** In-memory block index of earlier rows: blockKey => list of records. */
    private array $index = [];

    public function __construct(
        private readonly CandidateGatherer $gatherer,
        private readonly DuplicateCascade $cascade,
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
        // Pool = index-backed registry candidates + earlier rows in this batch. The
        // cascade evaluates NIN → BVN → fuzzy over the pool and stops at the first
        // exact stage. Registry ids are UUIDs; batch refs are row numbers, so we
        // recover each candidate's source by membership in the registry-id set.
        $registry = $this->gatherer->gather($candidate, $config);
        $registryIds = [];
        foreach ($registry as $record) {
            $registryIds[(string) $record['id']] = true;
        }

        $pool = array_merge($registry, $this->batchRecords($candidate));
        $outcome = $this->cascade->evaluate($candidate, $pool, $config);

        $this->remember($candidate, $rowNumber);

        $candidates = array_map(
            fn (MatchResult $result): array => $this->entry(
                isset($registryIds[(string) $result->reference]) ? 'registry' : 'batch',
                $result,
            ),
            $outcome['results'],
        );

        return ['band' => $outcome['band']->value, 'candidates' => $candidates];
    }

    /**
     * Earlier rows in the batch that share a blocking key with the candidate
     * (deduped), to be pooled with the registry candidates for the cascade.
     *
     * @param  array<string, mixed>  $candidate
     * @return list<array<string, mixed>>
     */
    private function batchRecords(array $candidate): array
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

        return $records;
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
}
