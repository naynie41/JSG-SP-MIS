<?php

declare(strict_types=1);

namespace App\Domain\Programme\Enums;

/**
 * Who pays for an activity.
 *
 * `Partner` is the only type linked to a Development Partner account, and that link is
 * what brings the activity into the partner's view. Government co-funding of a partner
 * activity is a separate flag on the activity, not a fourth type: the partner still
 * sees the whole activity either way.
 */
enum FundingType: string
{
    case Government = 'government';
    case Partner = 'partner';
    case Individual = 'individual';

    public function label(): string
    {
        return match ($this) {
            self::Government => 'Government funded',
            self::Partner => 'Social protection partner',
            self::Individual => 'Individuals',
        };
    }
}
