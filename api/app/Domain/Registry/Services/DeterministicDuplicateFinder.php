<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Engine\DeterministicMatcher;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Builder;

/**
 * Finds deterministic (exact-key) duplicates of a candidate against the existing
 * registry (PRD FR-DUP-03). The lookup is index-backed: it queries the
 * beneficiaries table on the configured key fields (default NIN/BVN), which are
 * covered by the Phase 2 partial-unique indexes — so the exact path never
 * full-table scans. The duplicate check spans all MDAs (register once, serve
 * many), so the owner scope is bypassed. Read-only: it surfaces matches, it does
 * not block.
 */
class DeterministicDuplicateFinder
{
    /** Fields materialised from a matched beneficiary for in-memory confirmation. */
    private const FIELDS = [
        'nin', 'bvn', 'phone', 'first_name', 'middle_name', 'last_name',
        'date_of_birth', 'gender', 'lga', 'ward',
    ];

    public function __construct(
        private readonly DeterministicMatcher $matcher,
        private readonly FieldNormalizer $normalizer,
        private readonly MatchingConfigService $configs,
    ) {}

    /**
     * @param  array<string, mixed>  $candidate
     * @return list<MatchResult>
     */
    public function find(array $candidate, ?MatchingConfig $config = null, ?string $excludeId = null): array
    {
        $config ??= $this->configs->active();

        $query = $this->buildQuery($candidate, $config, $excludeId);
        if ($query === null) {
            return []; // no usable deterministic key on the candidate → never scan
        }

        $records = [];
        foreach ($query->get() as $beneficiary) {
            /** @var Beneficiary $beneficiary */
            $records[] = $this->toRecord($beneficiary);
        }

        return $this->matcher->match($candidate, $records, $config);
    }

    /**
     * Build the index-backed exact-key query, or null when the candidate has no
     * usable deterministic key (guards against a full-table scan).
     *
     * @param  array<string, mixed>  $candidate
     */
    public function buildQuery(array $candidate, MatchingConfig $config, ?string $excludeId = null): ?Builder
    {
        $keySets = [];
        foreach ($config->deterministicKeySets() as $keySet) {
            $normalised = [];
            $usable = $keySet !== [];
            foreach ($keySet as $field) {
                $value = $this->normalizer->normalize($field, $candidate[$field] ?? null);
                if ($value === null) {
                    $usable = false;
                    break;
                }
                $normalised[$field] = $value;
            }
            if ($usable) {
                $keySets[] = $normalised;
            }
        }

        if ($keySets === []) {
            return null;
        }

        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);
        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        // Each key set is an ANDed equality group; the sets are ORed together.
        // Equality on nin/bvn uses the partial-unique indexes (no full scan).
        $query->where(function (Builder $outer) use ($keySets): void {
            foreach ($keySets as $set) {
                $outer->orWhere(function (Builder $inner) use ($set): void {
                    foreach ($set as $field => $value) {
                        $inner->where($field, $value);
                    }
                });
            }
        });

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function toRecord(Beneficiary $beneficiary): array
    {
        $record = ['id' => $beneficiary->id];
        foreach (self::FIELDS as $field) {
            $record[$field] = $beneficiary->getAttribute($field);
        }

        return $record;
    }
}
