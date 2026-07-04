<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Enums;

/**
 * The kind of benefit delivered (PRD FR-BEN-02). A fixed starter taxonomy — it can
 * be made configurable later without changing the ledger. `other` is the escape
 * hatch for anything not yet enumerated.
 */
enum BenefitType: string
{
    case Cash = 'cash';
    case Food = 'food';
    case AgriculturalInput = 'agricultural_input';
    case Training = 'training';
    case Health = 'health';
    case Education = 'education';
    case CashForWork = 'cash_for_work';
    case Other = 'other';
}
