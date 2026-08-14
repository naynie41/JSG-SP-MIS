<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

/**
 * Turns a messy source value into the form used for COMPARISON (PRD v1.7, CLAUDE.md §11).
 *
 * Pure: no database, no configuration, no clock. The same input always gives the same
 * output, which is what lets the duplicate cascade be reasoned about at all — a matcher
 * whose normalization depended on ambient state would produce different verdicts for the
 * same two records on different days.
 *
 * **The output is never what gets stored.** Every caller keeps the original value on the
 * record and uses these results only to compare, hash or block. "MOHAMMED  MUSA" is
 * stored exactly as the MDA wrote it; only the comparison sees "mohammed musa".
 */
final class NormalizationService
{
    /** Digits only — for NIN, BVN, and any other numeric identifier. */
    public function identifier(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return $digits === '' ? null : $digits;
    }

    /**
     * A Nigerian phone number in one canonical national form: `0` + 10 significant
     * digits (e.g. `08031234567`).
     *
     * Stripping non-digits alone is NOT enough, and treating it as enough is a silent
     * duplicate-detection failure: `+234 803 123 4567` and `08031234567` are the same
     * subscriber but reduce to `2348031234567` and `08031234567`, which no exact
     * comparator will ever match. The country code and trunk prefix have to be resolved.
     *
     * Anything that is not recognisably a Nigerian number is returned as bare digits
     * rather than forced into the pattern — a wrong guess would merge two people.
     */
    public function phone(?string $value): ?string
    {
        $digits = $this->identifier($value);
        if ($digits === null) {
            return null;
        }

        // International access prefix: 00234... → 234...
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        // Country code, with or without the trunk 0 that some sources keep after it
        // (+234 (0) 803 …). Both spellings denote the same subscriber.
        if (str_starts_with($digits, '234')) {
            $national = substr($digits, 3);
            $national = str_starts_with($national, '0') ? substr($national, 1) : $national;

            return strlen($national) === 10 ? '0'.$national : $digits;
        }

        // Local form without the trunk 0: 8031234567 → 08031234567.
        if (strlen($digits) === 10 && ! str_starts_with($digits, '0')) {
            return '0'.$digits;
        }

        return $digits;
    }

    /**
     * Case-folded, whitespace-collapsed text — the comparison form for names and other
     * free text. Punctuation is kept: `O'Brien` and `OBrien` are not obviously the same
     * name, and deciding that they are is the fuzzy comparator's job, not this one's.
     */
    public function name(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $collapsed = preg_replace('/\s+/u', ' ', trim($value)) ?? '';

        return $collapsed === '' ? null : mb_strtolower($collapsed);
    }

    /**
     * Name tokens sorted alphabetically, for ORDER-INSENSITIVE comparison —
     * "Musa Mohammed" and "Mohammed Musa" both give "mohammed musa".
     *
     * Deliberately opt-in and never the default. Field order carries meaning: a source
     * that writes surname first is not the same as one that writes it last, and folding
     * that away everywhere would let two genuinely different people match. Use it only
     * where a full-name column of unknown order is being compared.
     */
    public function nameTokensSorted(?string $value): ?string
    {
        $normalized = $this->name($value);
        if ($normalized === null) {
            return null;
        }

        $tokens = explode(' ', $normalized);
        sort($tokens);

        return implode(' ', $tokens);
    }

    /**
     * A date in ISO `Y-m-d`, parsed DAY-FIRST for ambiguous numeric formats.
     *
     * `strtotime()` is not usable here: it reads `12/03/1995` as 3 December (US
     * month-first) while `12-03-1995` is read as 12 March. Nigerian forms and exports
     * are written day-first, so the slash reading is wrong for this deployment — and a
     * misread birth date is not cosmetic: it shifts `block_name_dob`, which decides
     * which candidates the fuzzy matcher ever looks at.
     *
     * Unambiguous ISO input is taken as-is. Anything unparseable returns null rather
     * than a guess.
     */
    public function date(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        // Explicit formats, most-specific first. Day-first before month-first so an
        // ambiguous 12/03/1995 resolves to 12 March, matching how it was written.
        foreach (['Y-m-d', 'Y/m/d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'd M Y', 'd F Y', 'j/n/Y', 'j-n-Y'] as $format) {
            $parsed = \DateTimeImmutable::createFromFormat('!'.$format, $trimmed);
            if ($parsed !== false && $this->roundTrips($parsed, $format, $trimmed)) {
                return $parsed->format('Y-m-d');
            }
        }

        // Anything left that PHP understands unambiguously (e.g. "1995-03-12T00:00:00Z",
        // "12 March 1995"). Formats where PHP would apply the month-first reading have
        // already been handled above.
        $timestamp = strtotime($trimmed);

        return $timestamp === false ? null : date('Y-m-d', $timestamp);
    }

    /**
     * Guards against `createFromFormat` silently accepting an impossible date by
     * rolling it over — `31/02/1995` must not become 3 March.
     */
    private function roundTrips(\DateTimeImmutable $parsed, string $format, string $original): bool
    {
        return $parsed->format($format) === $original;
    }

    /**
     * Enum-ish values (gender, LGA): lower-cased, with spaces and hyphens folded to
     * underscores so `Birnin Kudu`, `birnin-kudu` and `BIRNIN_KUDU` all land on the
     * same canonical key.
     */
    public function enumKey(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        return (string) preg_replace('/[\s\-]+/', '_', mb_strtolower($trimmed));
    }

    /** Interpret a source truthy flag (household head, and similar). */
    public function boolean(?string $value): bool
    {
        return $value !== null && in_array(mb_strtolower(trim($value)), ['1', 'true', 'yes', 'y', 'head'], true);
    }

    /**
     * The comparison form for a canonical field, chosen by its declared type — the
     * single entry point callers should prefer over picking a method by hand.
     */
    public function forField(string $field, ?string $value): ?string
    {
        return match (CanonicalSchema::typeOf($field)) {
            'digits:11' => $this->identifier($value),
            'phone' => $this->phone($value),
            'date' => $this->date($value),
            'enum' => $this->enumKey($value),
            'boolean' => $this->boolean($value) ? '1' : '0',
            default => $this->name($value),
        };
    }
}
