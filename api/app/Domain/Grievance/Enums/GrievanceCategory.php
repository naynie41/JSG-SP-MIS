<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Enums;

/**
 * Grievance categories (PRD FR-GRM-01). A fixed starter taxonomy; extend as the
 * GRM matures (the stakeholder-confirmed list is a documented open question).
 */
enum GrievanceCategory: string
{
    case Payment = 'payment';
    case Eligibility = 'eligibility';
    case DataCorrection = 'data_correction';
    case ServiceQuality = 'service_quality';
    case Complaint = 'complaint';
    case Other = 'other';
}
