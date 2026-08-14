<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\Enums\MatchField;
use App\Domain\Registry\Support\NormalizationService;

/**
 * Normalises a raw field value into a comparable form before scoring.
 *
 * Both sides of a comparison pass through here, so this is where two written forms of
 * the same fact are reconciled. It delegates to the one {@see NormalizationService} that
 * the registry uses, rather than keeping a second set of rules: when the two drifted, a
 * value stored one way and compared another silently stopped matching.
 *
 * Two of those drifts were real and are fixed by delegating:
 *  - PHONE: digit-stripping alone left `+2348031234567` and `08031234567` as different
 *    strings, so the exact phone comparator never matched them.
 *  - DATE: `strtotime()` reads `12/03/1995` as 3 December (month-first). Sources here
 *    are written day-first, so the date — and the birth-year half of the blocking key —
 *    could be nine months out.
 */
class FieldNormalizer
{
    public function __construct(private readonly NormalizationService $normalizer = new NormalizationService) {}

    public function normalize(string $field, mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);
        if ($string === '') {
            return null;
        }

        $matchField = MatchField::tryFrom($field);

        if ($matchField === MatchField::Phone) {
            return $this->normalizer->phone($string);
        }

        if ($matchField !== null && $matchField->isNumericIdentifier()) {
            return $this->normalizer->identifier($string);
        }

        if ($matchField === MatchField::DateOfBirth) {
            // An unparseable date is compared as written rather than dropped: two
            // records carrying the same odd string are still evidence of a match.
            return $this->normalizer->date($string) ?? $this->normalizer->name($string);
        }

        return $this->normalizer->name($string);
    }
}
