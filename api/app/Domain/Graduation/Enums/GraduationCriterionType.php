<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Enums;

/**
 * A measurable dimension a graduation criterion can test (FR-GRD-01). Each is
 * evaluated from real registry/ledger data — never hard-coded thresholds. `Manual`
 * is met only by an explicit officer decision (progress can't auto-satisfy it).
 */
enum GraduationCriterionType: string
{
    case BenefitsReceived = 'benefits_received';   // count of benefits delivered in the programme
    case MonthsEnrolled = 'months_enrolled';       // months since enrolment
    case TotalBenefitValue = 'total_benefit_value'; // sum of benefit value (kobo) in the programme
    case Manual = 'manual';                        // officer confirms readiness

    public function label(): string
    {
        return match ($this) {
            self::BenefitsReceived => 'Benefits received',
            self::MonthsEnrolled => 'Months enrolled',
            self::TotalBenefitValue => 'Total benefit value',
            self::Manual => 'Manual readiness',
        };
    }

    /** Whether the dimension is computed from data (vs decided by an officer). */
    public function isAutomatic(): bool
    {
        return $this !== self::Manual;
    }
}
