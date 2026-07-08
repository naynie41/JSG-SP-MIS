<?php

declare(strict_types=1);

namespace App\Domain\Matching\Models;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Matching\Enums\ExactMatchBehaviour;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A versioned duplicate-matching configuration (PRD FR-DUP-02/03). Each row is an
 * immutable version; the active one drives the engine. Auditable, so every
 * published change is recorded.
 *
 * @property string $id
 * @property int $version
 * @property bool $is_active
 * @property list<list<string>> $deterministic_rules
 * @property list<array{field: string, comparator: string, weight: float}> $fuzzy_fields
 * @property float $review_threshold
 * @property float|null $auto_accept_threshold
 * @property ExactMatchBehaviour $exact_match_behaviour
 * @property string|null $description
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $author
 */
class MatchingConfig extends Model
{
    use Auditable, HasUuids;

    protected $table = 'matching_configs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'version',
        'is_active',
        'deterministic_rules',
        'fuzzy_fields',
        'review_threshold',
        'auto_accept_threshold',
        'exact_match_behaviour',
        'description',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deterministic_rules' => 'array',
            'fuzzy_fields' => 'array',
            'review_threshold' => 'float',
            'auto_accept_threshold' => 'float',
            'exact_match_behaviour' => ExactMatchBehaviour::class,
            'version' => 'integer',
        ];
    }

    /**
     * Deterministic exact-match key sets — a candidate matches if every field in
     * any one set is present and equal.
     *
     * @return list<list<string>>
     */
    public function deterministicKeySets(): array
    {
        return $this->deterministic_rules;
    }

    /**
     * @return list<array{field: string, comparator: string, weight: float}>
     */
    public function fuzzyFields(): array
    {
        return $this->fuzzy_fields;
    }

    /** Sum of the configured fuzzy weights (the score denominator). */
    public function totalFuzzyWeight(): float
    {
        return array_sum(array_map(static fn (array $f) => (float) $f['weight'], $this->fuzzy_fields));
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
