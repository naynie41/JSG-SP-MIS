<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * A Nigerian phone number, judged AFTER normalization (FR-REG-05, FR-REG-16).
 *
 * Phone is an IDENTITY field — it participates in the fuzzy match stage — but it was
 * validated only as `string|max:20`, so `not a phone` or `12` passed and reached the
 * matcher as a comparable value. A junk phone does not fail loudly; it quietly widens
 * or narrows who a row is compared against.
 *
 * Validity is checked on the NORMALIZED form so the many written spellings of one
 * number are judged as one thing: `+234 803 123 4567`, `00234 803 123 4567`,
 * `803 123 4567` and `0803 123 4567` all reduce to `08031234567` and are all valid.
 * The message DESCRIBES the failure and never prints the number — a phone is PII, and
 * this text reaches the row error report and the sync run log alike.
 */
class NigerianPhone implements DataAwareRule, DescribesConstraint, ValidationRule
{
    use QuotesOriginalInput;

    public function __construct(private readonly NormalizationService $normalizer = new NormalizationService) {}

    /** The shape this enforces, for the read-only rules page in the admin console. */
    public function constraintToken(): string
    {
        return 'nigerian_phone:'.$this->expectedDigits();
    }

    private function expectedDigits(): int
    {
        return (int) config('registry.identity.phone_national_digits', 11);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return; // absent is valid; `nullable` governs presence
        }

        $original = $this->originalFor($attribute, $value);
        $normalized = $this->normalizer->phone($original);
        $expected = $this->expectedDigits();

        /*
         * Described, never quoted: a phone number is PII (CLAUDE.md §8) and this text
         * reaches the row error report and the sync run log. The shape of the failure is
         * what makes it fixable; the row number is what locates it.
         */
        if ($normalized === null) {
            $fail('Phone must be a Nigerian number — this value contains no digits.');

            return;
        }

        // National form is 0 followed by the subscriber number. Anything that does not
        // reduce to it is either not Nigerian or is at the wrong length.
        if (! str_starts_with($normalized, '0') || strlen($normalized) !== $expected) {
            $digits = strlen($normalized);
            $fail(
                "Phone must be a Nigerian number of {$expected} digits (e.g. 08031234567) — ".
                "this value reads as {$digits} digit".($digits === 1 ? '' : 's').'.'
            );
        }
    }
}
