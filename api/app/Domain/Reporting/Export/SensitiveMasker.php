<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Export;

/**
 * Masks sensitive identifiers (NIN/BVN/phone) in exports (SECURITY.md §1/§8). Same
 * "last-4 only" rule the beneficiary reveal uses, so a raw identifier can never leave
 * the system in a report file.
 */
final class SensitiveMasker
{
    public static function mask(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return str_repeat('•', max(0, mb_strlen($value) - 4)).mb_substr($value, -4);
    }
}
