<?php

declare(strict_types=1);

namespace App\Domain\Matching\Enums;

/**
 * Beneficiary fields that matching rules may reference (PRD FR-DUP-02). Used to
 * validate admin-supplied configuration and to normalise values before comparison.
 */
enum MatchField: string
{
    case Nin = 'nin';
    case Bvn = 'bvn';
    case Phone = 'phone';
    case FirstName = 'first_name';
    case MiddleName = 'middle_name';
    case LastName = 'last_name';
    case DateOfBirth = 'date_of_birth';
    case Gender = 'gender';
    case Lga = 'lga';
    case Ward = 'ward';

    /** Identifier-style fields are normalised to digits only before comparison. */
    public function isNumericIdentifier(): bool
    {
        return in_array($this, [self::Nin, self::Bvn, self::Phone], true);
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }
}
