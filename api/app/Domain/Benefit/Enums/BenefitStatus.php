<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Enums;

/**
 * Ledger status of a benefit record (PRD FR-BEN-02). `recorded` = delivery logged;
 * `verified` = delivery confirmed (FR-BEN-04); `reversed` = corrected/voided in the
 * ledger. This never represents a money movement — the ledger records delivery,
 * not disbursement (§2.3).
 */
enum BenefitStatus: string
{
    case Recorded = 'recorded';
    case Verified = 'verified';
    case Reversed = 'reversed';
}
