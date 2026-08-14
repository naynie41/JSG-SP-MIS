<?php

declare(strict_types=1);

namespace Tests\Unit\Registry;

use App\Domain\Registry\Support\BeneficiaryRules;
use App\Domain\Registry\Support\CanonicalSchema;
use PHPUnit\Framework\TestCase;

/**
 * The canonical schema is the single declaration of what a beneficiary IS (FR-REG-04).
 *
 * The field list previously existed in three places — the import validator, the sync
 * engine and BeneficiaryRules. Copies like that fail quietly: a field added to one door
 * is silently dropped by another, and nothing tells you until data goes missing. These
 * tests keep the remaining published copies pinned to the schema.
 */
class CanonicalSchemaTest extends TestCase
{
    public function test_it_declares_every_field_fr_reg_04_requires(): void
    {
        // NIN, BVN, phone, name, DOB, gender, address, LGA/Ward, household reference.
        foreach (['nin', 'bvn', 'phone', 'first_name', 'last_name', 'date_of_birth', 'gender', 'address', 'lga', 'ward'] as $field) {
            $this->assertContains($field, CanonicalSchema::fields(), "FR-REG-04 requires {$field}");
        }

        $this->assertContains('household_ref', CanonicalSchema::allFields(), 'FR-REG-04 requires a household reference');
    }

    public function test_the_identity_split_matches_the_published_rules(): void
    {
        // BeneficiaryRules publishes these through an admin endpoint; if the two ever
        // disagree, one part of the system rejects a row that another accepts.
        $this->assertSame(
            $this->sorted(BeneficiaryRules::IDENTITY_FIELDS),
            $this->sorted(CanonicalSchema::identityFields()),
        );
        $this->assertSame(
            $this->sorted(BeneficiaryRules::NON_IDENTITY_FIELDS),
            $this->sorted(CanonicalSchema::nonIdentityFields()),
        );
    }

    public function test_required_and_identity_are_different_questions(): void
    {
        // An optional field can still be an identity field: an absent NIN is fine, a
        // malformed one rejects the whole row (FR-REG-05). Conflating the two would
        // either reject rows with no NIN or accept a malformed one.
        $this->assertTrue(CanonicalSchema::isIdentityField('nin'));
        $this->assertNotContains('nin', CanonicalSchema::requiredFields());

        // And the converse: DOB must be present, but a bad one only drops the field.
        $this->assertContains('date_of_birth', CanonicalSchema::requiredFields());
        $this->assertFalse(CanonicalSchema::isIdentityField('date_of_birth'));
    }

    public function test_every_field_declares_a_type_used_by_normalization(): void
    {
        foreach (CanonicalSchema::allFields() as $field) {
            $this->assertNotNull(CanonicalSchema::typeOf($field), "{$field} has no declared type");
        }

        $this->assertSame('phone', CanonicalSchema::typeOf('phone'));
        $this->assertSame('digits:11', CanonicalSchema::typeOf('nin'));
        $this->assertSame('date', CanonicalSchema::typeOf('date_of_birth'));
    }

    public function test_an_unknown_field_has_no_type(): void
    {
        $this->assertNull(CanonicalSchema::typeOf('favourite_colour'));
        $this->assertFalse(CanonicalSchema::isIdentityField('favourite_colour'));
    }

    /**
     * @param  list<string>  $values
     * @return list<string>
     */
    private function sorted(array $values): array
    {
        sort($values);

        return $values;
    }
}
