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
    case AdminGrant = 'admin_grant';    // an administrative whole-MDA grant (FR-UAM-03)
    case None = 'none';                 // no basis — access is denied

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Owner MDA',
            self::Oversight => 'Oversight (cross-MDA)',
            self::ServiceGrant => 'Request-to-serve grant',
            self::AdminGrant => 'Administrative cross-MDA grant',
            self::None => 'No basis',
        };
    }

    /**
     * What the basis opens. A service grant is per-BENEFICIARY (the one record the owner
     * approved); an administrative grant is per-MDA (the grantee sees that MDA's scoped
     * data at large). Oversight is platform-wide. The distinction matters to whoever
     * reviews the data-sharing report — blurring it would hide the widest grant type.
     */
    public function scope(): string
    {
        return match ($this) {
            self::Owner, self::ServiceGrant => 'beneficiary',
            self::AdminGrant => 'mda',
            self::Oversight => 'platform',
            self::None => 'none',
        };
    }

    /** Whether this basis crosses an MDA boundary (and so is a sharing event to log). */
    public function isCrossMda(): bool
    {
        return $this !== self::Owner && $this !== self::None;
    }
}
