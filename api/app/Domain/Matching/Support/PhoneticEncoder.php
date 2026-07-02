<?php

declare(strict_types=1);

namespace App\Domain\Matching\Support;

/**
 * A lightweight phonetic encoder tuned for Hausa/Nigerian name variance
 * (PRD FR-DUP-03). It folds common spelling variants onto a shared consonant
 * skeleton so that, e.g., Muhammad / Mohammed / Muhammadu, Ibrahim / Ibraheem,
 * Sani / Saani, and Sadiq / Saddiq / Sadqi (transposed) all encode alike.
 *
 * Used two ways: the full `code()` drives the phonetic name comparator; the short
 * `block()` prefix drives candidate blocking (higher recall for the same key).
 */
class PhoneticEncoder
{
    private const VOWELS = ['a', 'e', 'i', 'o', 'u'];

    /** Common cluster folds before skeletonisation. */
    private const FOLDS = ['ph' => 'f', 'ck' => 'k', 'q' => 'k', 'x' => 'ks'];

    /** Full consonant-skeleton code (first letter preserved, doubles collapsed). */
    public function code(string $name): string
    {
        $name = strtolower($name);
        $name = preg_replace('/[^a-z]/', '', $name) ?? '';
        if ($name === '') {
            return '';
        }

        $name = strtr($name, self::FOLDS);
        $first = $name[0];
        $rest = str_replace(self::VOWELS, '', substr($name, 1));

        $code = $first.$rest;
        $code = preg_replace('/(.)\1+/', '$1', $code) ?? $code; // collapse duplicates

        return strtoupper($code);
    }

    /** Short blocking prefix of the code (groups more variants together). */
    public function block(string $name): string
    {
        return substr($this->code($name), 0, 3);
    }
}
