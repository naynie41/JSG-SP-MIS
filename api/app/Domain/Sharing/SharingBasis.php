<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

/**
 * The single legal basis on which a user/MDA may see or serve a beneficiary across
 * MDA boundaries (FR-DSH-01). Every cross-MDA decision resolves to exactly one of
 * these — there is no other sharing path.
 */
enum SharingBasis: string
{
    case Owner = 'owner';               // the beneficiary's owner MDA
    case Oversight = 'oversight';       // a cross-mda.view (M&E/executive) role
    case ServiceGrant = 'service_grant'; // an accepted Service Request read/serve grant
    case None = 'none';                 // no basis — access is denied

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Owner MDA',
            self::Oversight => 'Oversight (cross-MDA)',
            self::ServiceGrant => 'Request-to-serve grant',
            self::None => 'No basis',
        };
    }
}
