<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\Enums\MatchField;

/**
 * Normalises a raw field value into a comparable form before scoring:
 * identifiers → digits only; dates → `Y-m-d`; everything else → lower-cased,
 * whitespace-collapsed text. Returns null when nothing comparable remains.
 */
class FieldNormalizer
{
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

        if ($matchField !== null && $matchField->isNumericIdentifier()) {
            $digits = preg_replace('/\D+/', '', $string) ?? '';

            return $digits === '' ? null : $digits;
        }

        if ($matchField === MatchField::DateOfBirth) {
            $timestamp = strtotime($string);

            return $timestamp === false ? $string : date('Y-m-d', $timestamp);
        }

        // Text fields: lower-case + collapse internal whitespace.
        $text = mb_strtolower($string);

        return (string) preg_replace('/\s+/', ' ', $text);
    }
}
