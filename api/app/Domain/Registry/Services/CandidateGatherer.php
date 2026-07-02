<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Builder;

/**
 * Candidate blocking for fuzzy matching (PRD FR-DUP-03, NFR-PERF-01). Instead of
 * scoring against the whole registry, it gathers only records that share a
 * blocking key with the candidate — all index-backed exact lookups:
 *
 *  - NIN / BVN (partial-unique indexes)
 *  - phone (indexed)
 *  - `block_name_dob` = phonetic(last_name)|dob_year (indexed)
 *
 * The gathered set is hard-capped, so even a very common block stays bounded.
 * Cross-MDA (duplicates span MDAs); read-only.
 */
class CandidateGatherer
{
    /** Safety cap on the candidate set regardless of block size. */
    public const MAX_CANDIDATES = 200;

    private const FIELDS = [
        'nin', 'bvn', 'phone', 'first_name', 'middle_name', 'last_name',
        'date_of_birth', 'gender', 'lga', 'ward',
    ];

    public function __construct(private readonly FieldNormalizer $normalizer) {}

    /**
     * @param  array<string, mixed>  $candidate
     * @return list<array<string, mixed>>
     */
    public function gather(array $candidate, MatchingConfig $config, ?string $excludeId = null): array
    {
        $query = $this->buildQuery($candidate, $config, $excludeId);
        if ($query === null) {
            return [];
        }

        $records = [];
        foreach ($query->limit(self::MAX_CANDIDATES)->get() as $beneficiary) {
            /** @var Beneficiary $beneficiary */
            $record = ['id' => $beneficiary->id];
            foreach (self::FIELDS as $field) {
                $record[$field] = $beneficiary->getAttribute($field);
            }
            $records[] = $record;
        }

        return $records;
    }

    /**
     * The index-backed blocking query, or null when the candidate yields no usable
     * blocking key (so we never fall back to a full-table scan).
     *
     * @param  array<string, mixed>  $candidate
     */
    public function buildQuery(array $candidate, MatchingConfig $config, ?string $excludeId = null): ?Builder
    {
        $nin = $this->normalizer->normalize('nin', $candidate['nin'] ?? null);
        $bvn = $this->normalizer->normalize('bvn', $candidate['bvn'] ?? null);
        $phone = $this->normalizer->normalize('phone', $candidate['phone'] ?? null);
        $nameDob = Beneficiary::blockNameDobFor(
            isset($candidate['last_name']) ? (string) $candidate['last_name'] : null,
            isset($candidate['date_of_birth']) ? (string) $candidate['date_of_birth'] : null,
        );

        $blocks = array_filter([
            'nin' => $nin,
            'bvn' => $bvn,
            'phone' => $phone,
            'block_name_dob' => $nameDob,
        ], static fn (?string $value) => $value !== null && $value !== '');

        if ($blocks === []) {
            return null;
        }

        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);
        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        $query->where(function (Builder $outer) use ($blocks): void {
            foreach ($blocks as $column => $value) {
                $outer->orWhere($column, $value);
            }
        });

        return $query;
    }
}
