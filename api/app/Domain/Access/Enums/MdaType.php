<?php

declare(strict_types=1);

namespace App\Domain\Access\Enums;

/**
 * The kind of organisation that DELIVERS social protection (PRD §9, revised).
 *
 * The first three are government: Ministries, Departments and Agencies — the "MDA"
 * the table and the `owner_mda_id` columns are named after. `Partner` is not
 * government at all: a development partner that implements its own programmes
 * rather than only funding someone else's.
 *
 * A partner organisation is an owner like any other, which is the whole reason it is
 * modelled here instead of somewhere new: MDA scoping, duplicate detection,
 * request-to-serve, imports and the benefit ledger all key off `owner_mda_id` and
 * work unchanged. What it must NOT do is read as government in the interface —
 * `isGovernment()` on the model is how a screen tells them apart.
 */
enum MdaType: string
{
    case Ministry = 'ministry';
    case Department = 'department';
    case Agency = 'agency';
    case Partner = 'partner';

    public function label(): string
    {
        return match ($this) {
            self::Ministry => 'Ministry',
            self::Department => 'Department',
            self::Agency => 'Agency',
            self::Partner => 'Development partner',
        };
    }

    /** A government body, as opposed to a partner organisation that implements. */
    public function isGovernment(): bool
    {
        return $this !== self::Partner;
    }
}
