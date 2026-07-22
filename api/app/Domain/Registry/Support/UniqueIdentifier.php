<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Registry-wide uniqueness for an encrypted national identifier (FR-REG-04/05).
 *
 * NIN/BVN are encrypted at rest, so `Rule::unique` on the value column cannot
 * work; this rule checks the deterministic keyed hash column instead — the same
 * column the partial-unique DB indexes guard. Cross-MDA (register once, serve
 * many), mirroring the old plaintext behaviour exactly.
 */
class UniqueIdentifier implements ValidationRule
{
    public function __construct(
        private readonly string $field,
        private readonly string $message,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = Beneficiary::normalizeDigits(is_string($value) ? $value : null);
        if ($normalized === null) {
            return;
        }

        // withTrashed + scope bypass mirror the old DB-level Rule::unique exactly:
        // it saw every row (soft-deleted included) across all MDAs.
        $exists = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->withTrashed()
            ->where($this->field.'_hash', IdentifierHasher::hash($normalized))
            ->exists();

        if ($exists) {
            $fail($this->message);
        }
    }
}
