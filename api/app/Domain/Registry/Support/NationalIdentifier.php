<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A fixed-length numeric national identifier — NIN or BVN (FR-REG-05).
 *
 * Replaces a bare `digits:11`, whose message ("The nin field must be 11 digits.") does
 * not say what was actually supplied. On a 200-row file the officer has to find the bad
 * row themselves; "got 9 digits" turns that into a search.
 *
 * The length lives in `config/registry.identity`, not here — one place for the shape of
 * an identifier, changeable in a single edit rather than per call site.
 *
 * Judged on the NORMALIZED value (digits only, punctuation stripped), so
 * `221-000-000-11` is eleven digits rather than a formatting failure. It reads the
 * ORIGINAL to tell "wrong length" from "not a number at all", but never prints it.
 */
class NationalIdentifier implements DataAwareRule, DescribesConstraint, ValidationRule
{
    use QuotesOriginalInput;

    /**
     * @param  'nin'|'bvn'  $field
     */
    public function __construct(
        private readonly string $field,
        private readonly NormalizationService $normalizer = new NormalizationService,
    ) {}

    /** The shape this enforces, for the read-only rules page in the admin console. */
    public function constraintToken(): string
    {
        return 'digits:'.$this->expectedDigits();
    }

    private function expectedDigits(): int
    {
        return (int) config("registry.identity.{$this->field}_digits", 11);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return; // an absent optional NIN/BVN is valid (FR-REG-05)
        }

        // The row is validated on NORMALIZED values, but the ORIGINAL is what separates
        // "wrong length" from "not a number at all": normalization strips the very
        // characters that made it not a number, so the normalized form cannot tell them apart.
        $original = $this->originalFor($attribute, $value);
        $expected = $this->expectedDigits();
        $label = strtoupper($this->field);

        /*
         * The message DESCRIBES the offending value; it never quotes it.
         *
         * A NIN or BVN is PII (CLAUDE.md §8) and this text lands in the row error report
         * and the sync run log, both of which are read across MDAs. "has 9 digits" tells
         * the officer exactly what to fix; printing the number would put an identifier
         * that failed validation into a log in the clear. The ROW NUMBER, carried
         * alongside, is how they find the cell.
         */
        if (preg_match('/[^\d\s\-.()]/', $original) === 1) {
            $fail("{$label} must be {$expected} numeric digits — this value contains characters that are not digits.");

            return;
        }

        $length = strlen($this->normalizer->identifier($original) ?? '');

        if ($length !== $expected) {
            $fail("{$label} must be exactly {$expected} digits — this value has {$length}.");
        }
    }
}
