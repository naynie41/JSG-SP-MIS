<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class RegistrySchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_beneficiary_sets_provenance_and_writes_an_audit_entry(): void
    {
        $mda = Mda::factory()->create();

        $beneficiary = Beneficiary::create([
            'owner_mda_id' => $mda->id,
            'first_name' => 'Amina',
            'last_name' => 'Bello',
            'nin' => '12345678901',
        ]);

        // Provenance defaulted (FR-REG-03).
        $this->assertSame(RegistrationSource::Manual, $beneficiary->registration_source);
        $this->assertTrue($beneficiary->registration_date->isToday());

        // Audited via the Auditable trait (FR-AUD-01).
        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.created',
            'entity_type' => 'beneficiary',
            'entity_id' => $beneficiary->id,
        ]);
    }

    public function test_identifiers_are_reduced_to_digits_on_save(): void
    {
        // NIN/BVN keep digit normalization: `digits:11` is the validated shape and the
        // punctuation around an identifier carries no information.
        $beneficiary = Beneficiary::factory()->create([
            'nin' => '123-456-789 01',
            'bvn' => '109 876 543 21',
        ]);

        $fresh = $beneficiary->fresh();
        $this->assertSame('12345678901', $fresh->nin);
        $this->assertSame('10987654321', $fresh->bvn);
    }

    /**
     * Phone keeps the form the source wrote and gains a separate comparable form
     * (CLAUDE.md §11 — "normalization is for comparison only; the original value is
     * always stored"). Previously the written value was overwritten in place, which both
     * lost the original and left `+234…` and `0…` as different strings that the exact
     * phone comparator could never match.
     */
    public function test_the_written_phone_is_preserved_and_a_compare_form_derived(): void
    {
        $beneficiary = Beneficiary::factory()->create(['phone' => '+234 803 123 4567']);

        $fresh = $beneficiary->fresh();
        $this->assertSame('+234 803 123 4567', $fresh->phone, 'the original must survive');
        $this->assertSame('08031234567', $fresh->phone_normalized);
    }

    public function test_two_written_forms_of_one_number_share_a_compare_form(): void
    {
        $a = Beneficiary::factory()->create(['phone' => '+2348031234567']);
        $b = Beneficiary::factory()->create(['phone' => '08031234567']);

        // Same subscriber, two spellings — comparable at last, while each record still
        // shows what its own source actually wrote.
        $this->assertSame($a->fresh()->phone_normalized, $b->fresh()->phone_normalized);
        $this->assertNotSame($a->fresh()->phone, $b->fresh()->phone);
    }

    public function test_invalid_nin_length_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Beneficiary::factory()->create(['nin' => '123']);
    }

    public function test_nin_uniqueness_is_conditional_on_non_null(): void
    {
        // Multiple NULL NINs are allowed (partial unique index).
        Beneficiary::factory()->withoutNin()->count(2)->create();
        $this->assertSame(2, Beneficiary::whereNull('nin')->count());

        // A duplicate non-null NIN is rejected.
        Beneficiary::factory()->create(['nin' => '11111111111']);
        $this->expectException(QueryException::class);
        Beneficiary::factory()->create(['nin' => '11111111111']);
    }

    public function test_bvn_uniqueness_is_conditional_on_non_null(): void
    {
        Beneficiary::factory()->withoutBvn()->count(2)->create();
        $this->assertSame(2, Beneficiary::whereNull('bvn')->count());

        Beneficiary::factory()->create(['bvn' => '22222222222']);
        $this->expectException(QueryException::class);
        Beneficiary::factory()->create(['bvn' => '22222222222']);
    }

    public function test_a_beneficiary_can_have_only_one_open_membership(): void
    {
        $beneficiary = Beneficiary::factory()->create();
        $householdA = Household::factory()->create();
        $householdB = Household::factory()->create();

        HouseholdMembership::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'household_id' => $householdA->id,
        ]);

        $this->expectException(QueryException::class);
        HouseholdMembership::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'household_id' => $householdB->id,
        ]);
    }

    public function test_closing_a_membership_allows_opening_a_new_one(): void
    {
        $beneficiary = Beneficiary::factory()->create();
        $householdA = Household::factory()->create();
        $householdB = Household::factory()->create();

        $first = HouseholdMembership::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'household_id' => $householdA->id,
        ]);

        // Close the current membership, then open a new one — a "move".
        $first->update(['left_at' => now()]);
        HouseholdMembership::factory()->create([
            'beneficiary_id' => $beneficiary->id,
            'household_id' => $householdB->id,
        ]);

        $this->assertSame(2, HouseholdMembership::where('beneficiary_id', $beneficiary->id)->count());
        $this->assertSame(1, HouseholdMembership::where('beneficiary_id', $beneficiary->id)->whereNull('left_at')->count());
    }
}
