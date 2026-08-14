<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Where a beneficiary/household record came from (PRD §6.1 provenance).
 *
 * Ingestion is bulk/source-only (CLAUDE.md §8) — every record enters through a file
 * import, the REST intake, a connector sync, or an offline batch, and each of those
 * knows its own source. There is no path by which a record's origin is unknown.
 */
enum RegistrationSource: string
{
    /**
     * @deprecated Historical only. Manual single-record entry was removed (CLAUDE.md §8),
     * so no NEW record may carry this value — a record tagged `manual` today would be
     * claiming an origin that cannot occur, which is a lineage the audit trail cannot
     * account for.
     *
     * The case remains ONLY so rows written before the removal still cast and display.
     * Deleting it would break reading that history, which is worse than keeping a value
     * that {@see self::isAssignable()} refuses for writes.
     */
    case Manual = 'manual';

    case Excel = 'excel';
    case Csv = 'csv';
    case Kobo = 'kobo';
    case Odk = 'odk';
    case Api = 'api';
    case Socu = 'socu';
    case GovernmentSystem = 'government_system';

    /** Whether a NEW record may be written with this provenance. */
    public function isAssignable(): bool
    {
        return $this !== self::Manual;
    }

    /**
     * The provenances a new record may carry.
     *
     * @return list<self>
     */
    public static function assignable(): array
    {
        return array_values(array_filter(self::cases(), static fn (self $case): bool => $case->isAssignable()));
    }

    /**
     * @return list<string>
     */
    public static function assignableValues(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::assignable());
    }
}
