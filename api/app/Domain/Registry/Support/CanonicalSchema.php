<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Registry\Imports\ImportRowValidator;
use App\Domain\Registry\Services\HouseholdIngestionService;

/**
 * The canonical SP-MIS beneficiary schema (PRD FR-REG-04).
 *
 * This is SP-MIS's INTERNAL standard, not a demand on the MDAs. A source file may use
 * any column names it likes; the Data Import & Mapping layer maps whatever arrives onto
 * these fields, and everything downstream — validation, the duplicate cascade, household
 * formation, the registrar — speaks only this vocabulary.
 *
 * It exists as one declaration because the field list had drifted into three copies
 * ({@see ImportRowValidator}, `SyncEngine::CANONICAL_FIELDS`
 * and {@see BeneficiaryRules}), which is how a field ends up honoured on one ingestion
 * door and silently dropped on another.
 *
 * Two groupings matter and are NOT the same thing:
 *  - REQUIRED vs OPTIONAL — whether a value must be present at all.
 *  - IDENTITY vs NON-IDENTITY — what happens when a PRESENT value is malformed
 *    (FR-REG-05: identity rejects the whole row; FR-REG-09: non-identity drops the field
 *    and keeps the row). An optional field can still be an identity field: an absent NIN
 *    is fine, a malformed one rejects the row.
 */
final class CanonicalSchema
{
    /**
     * Core beneficiary fields, in presentation order.
     *
     * `required` is about presence; `identity` is about the malformed-value rule.
     *
     * @var array<string, array{type: string, required: bool, identity: bool, note: string}>
     */
    public const FIELDS = [
        'first_name' => ['type' => 'string', 'required' => true, 'identity' => true, 'note' => 'Given name'],
        'middle_name' => ['type' => 'string', 'required' => false, 'identity' => true, 'note' => 'Other name(s)'],
        'last_name' => ['type' => 'string', 'required' => true, 'identity' => true, 'note' => 'Surname; also the fuzzy blocking key'],
        'nin' => ['type' => 'digits:11', 'required' => false, 'identity' => true, 'note' => 'National Identification Number; deterministic match stage 1'],
        'bvn' => ['type' => 'digits:11', 'required' => false, 'identity' => true, 'note' => 'Bank Verification Number; deterministic match stage 2'],
        'phone' => ['type' => 'phone', 'required' => false, 'identity' => true, 'note' => 'Nigerian MSISDN in any written form'],
        'date_of_birth' => ['type' => 'date', 'required' => true, 'identity' => false, 'note' => 'Must be in the past; part of the blocking key'],
        'gender' => ['type' => 'enum', 'required' => true, 'identity' => false, 'note' => 'male | female | other'],
        'address' => ['type' => 'string', 'required' => false, 'identity' => false, 'note' => 'Residential address'],
        'lga' => ['type' => 'enum', 'required' => true, 'identity' => false, 'note' => 'A Jigawa LGA'],
        'ward' => ['type' => 'string', 'required' => true, 'identity' => false, 'note' => 'Ward within the LGA'],
    ];

    /**
     * Household grouping (FR-REG-04 "household reference"). Not beneficiary columns —
     * they drive {@see HouseholdIngestionService}, which
     * forms the household and opens a membership.
     *
     * @var array<string, array{type: string, required: bool, identity: bool, note: string}>
     */
    public const HOUSEHOLD_FIELDS = [
        'household_ref' => ['type' => 'string', 'required' => false, 'identity' => false, 'note' => "The source's own household key"],
        'household_role' => ['type' => 'enum', 'required' => false, 'identity' => false, 'note' => 'head | spouse | child | parent | sibling | other'],
        'household_head' => ['type' => 'boolean', 'required' => false, 'identity' => false, 'note' => 'Truthy marks this person as the head'],
    ];

    /** Provenance the source supplies; the rest is stamped by the registrar. */
    public const PROVENANCE_FIELDS = [
        'original_record_id' => ['type' => 'string', 'required' => false, 'identity' => false, 'note' => "The source's own record id; doubles as the idempotency key"],
    ];

    /**
     * Source-shape fields that are MAPPABLE but are not beneficiary columns — they are
     * derived into canonical fields at mapping time and never stored under these names.
     *
     * `full_name` exists because many MDA exports carry one `Name` column. Mapping such a
     * column to both `first_name` and `last_name` is what produced records reading
     * "Rekiya Bagwai Rekiya Bagwai"; mapping it here instead lets
     * {@see NameSplitter} derive the two properly. It is an
     * IDENTITY field: it is the name, so a malformed value must reject the row exactly as
     * a malformed `first_name` would.
     *
     * @var array<string, array{type: string, required: bool, identity: bool, note: string}>
     */
    public const DERIVED_SOURCE_FIELDS = [
        'full_name' => ['type' => 'string', 'required' => false, 'identity' => true, 'note' => 'One name column; split into first/last name'],
    ];

    /** @return list<string> */
    public static function fields(): array
    {
        return array_keys(self::FIELDS);
    }

    /**
     * Everything a column may be mapped ONTO, including the derived source fields.
     *
     * @return list<string>
     */
    public static function mappableFields(): array
    {
        return [...self::allFields(), ...array_keys(self::DERIVED_SOURCE_FIELDS)];
    }

    /** @return list<string> */
    public static function allFields(): array
    {
        return [...array_keys(self::FIELDS), ...array_keys(self::HOUSEHOLD_FIELDS), ...array_keys(self::PROVENANCE_FIELDS)];
    }

    /**
     * Fields whose PRESENT-but-malformed value rejects the whole row (FR-REG-05).
     *
     * @return list<string>
     */
    public static function identityFields(): array
    {
        return array_keys(array_filter(self::FIELDS, static fn (array $spec): bool => $spec['identity']));
    }

    /**
     * Fields whose failure drops just that value and keeps the row (FR-REG-09).
     *
     * @return list<string>
     */
    public static function nonIdentityFields(): array
    {
        return array_keys(array_filter(self::FIELDS, static fn (array $spec): bool => ! $spec['identity']));
    }

    /** @return list<string> */
    public static function requiredFields(): array
    {
        return array_keys(array_filter(self::FIELDS, static fn (array $spec): bool => $spec['required']));
    }

    public static function isIdentityField(string $field): bool
    {
        return self::FIELDS[$field]['identity'] ?? false;
    }

    /**
     * The fields whose column mapping a human must confirm on EVERY import
     * (CLAUDE.md §11): NIN, BVN, name and phone.
     *
     * These are the values the duplicate cascade treats as identity. A wrong mapping
     * here does not produce a validation error — it produces a confident, wrong answer:
     * a `national_id` column holding voter's-card numbers mapped to NIN makes the
     * deterministic stage declare two strangers the same person.
     *
     * `middle_name` is excluded deliberately. It is an identity field for the
     * malformed-value rule, but sources routinely omit it and requiring a decision on it
     * would add a click that carries no risk — and a guard people click through
     * mechanically stops being a guard.
     *
     * @return list<string>
     */
    public static function confirmationRequiredFields(): array
    {
        // `full_name` is here for the same reason as first/last name: it IS the name.
        // Left out, a confident "Name" → full_name suggestion would be auto-applied and
        // would then populate first and last name with no human ever confirming which
        // column the name came from — precisely what CLAUDE.md §11 forbids. Answering
        // "not present" is one click for the many files that have no such column.
        return ['first_name', 'last_name', 'full_name', 'nin', 'bvn', 'phone'];
    }

    /** The declared type of a canonical field, or null when the field is unknown. */
    public static function typeOf(string $field): ?string
    {
        return self::FIELDS[$field]['type']
            ?? self::HOUSEHOLD_FIELDS[$field]['type']
            ?? self::PROVENANCE_FIELDS[$field]['type']
            ?? self::DERIVED_SOURCE_FIELDS[$field]['type']
            ?? null;
    }
}
