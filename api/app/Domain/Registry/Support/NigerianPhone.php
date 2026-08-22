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
 * The ORIGINAL is what the message quotes back, because that is what the officer typed
 * and what they will search their file for.
 */
class NigerianPhone implements ValidationRule
{
    public function __construct(private readonly NormalizationService $normalizer = new NormalizationService) {}

    /**
     * @param  Closure(string): void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return; // absent is valid; `nullable` governs presence
        }

        $original = (string) $value;
        $normalized = $this->normalizer->phone($original);
        $expected = (int) config('registry.identity.phone_national_digits', 11);

        if ($normalized === null) {
            $fail("Phone must be a Nigerian number — “{$original}” has no digits.");

            return;
        }

        // National form is 0 followed by the subscriber number. Anything that does not
        // reduce to it is either not Nigerian or is missing/at the wrong length.
        if (! str_starts_with($normalized, '0') || strlen($normalized) !== $expected) {
            $digits = strlen($normalized);
            $fail(
                "Phone must be a Nigerian number of {$expected} digits (e.g. 08031234567) — ".
                "“{$original}” reads as {$digits} digit".($digits === 1 ? '' : 's').'.'
            );
        }
    }
}
