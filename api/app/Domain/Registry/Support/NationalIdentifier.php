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
 * `221-000-000-11` is eleven digits rather than a formatting failure. The message quotes
 * the ORIGINAL, because that is the string in the officer's file.
 */
class NationalIdentifier implements DataAwareRule, ValidationRule
{
    use QuotesOriginalInput;

    /**
     * @param  'nin'|'bvn'  $field
     */
    public function __construct(
        private readonly string $field,
        private readonly NormalizationService $normalizer = new NormalizationService,
    ) {}

    /**
     * @param  Closure(string): void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return; // an absent optional NIN/BVN is valid (FR-REG-05)
        }

        // The row is validated on NORMALIZED values, but the officer has to find the
        // problem in THEIR file — so the message quotes what they actually wrote.
        $original = $this->originalFor($attribute, $value);
        $expected = (int) config("registry.identity.{$this->field}_digits", 11);
        $label = strtoupper($this->field);

        // Anything that is not a digit once punctuation is removed — letters, symbols —
        // is a different failure from "wrong length" and is worth saying so.
        $digitsOnly = preg_replace('/\D+/', '', $original) ?? '';
        if ($digitsOnly !== $original && preg_match('/[^\d\s\-.()]/', $original) === 1) {
            $fail("{$label} must be {$expected} numeric digits — “{$original}” contains characters that are not digits.");

            return;
        }

        $normalized = $this->normalizer->identifier($original) ?? '';
        $length = strlen($normalized);

        if ($length !== $expected) {
            $fail("{$label} must be exactly {$expected} digits — “{$original}” has {$length}.");
        }
    }
}
