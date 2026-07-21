<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * A beneficiary's cross-MDA data-sharing consent state (NFR-PRV-01, FR-DSH-01).
 * `Unknown` is the safe default for a freshly ingested record — no consent is
 * assumed until it is captured. Only `Granted` satisfies the consent gate.
 */
enum ConsentStatus: string
{
    case Unknown = 'unknown';
    case Granted = 'granted';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Unknown => 'Not captured',
            self::Granted => 'Granted',
            self::Withdrawn => 'Withdrawn',
        };
    }
}
