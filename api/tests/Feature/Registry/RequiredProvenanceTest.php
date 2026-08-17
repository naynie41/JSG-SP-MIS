<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Tests\TestCase;

/**
 * Every registry record must say where it came from (FR-REG-03, CLAUDE.md §8).
 *
 * Provenance used to DEFAULT to `manual`. Once manual single-record entry was removed,
 * that default meant any record saved without an explicit source silently claimed an
 * origin that can no longer occur — corrupt lineage, and the worst kind: plausible. A
 * record tagged `manual` is indistinguishable in the audit trail from one that genuinely
 * arrived that way before the removal, so nobody would ever notice.
 *
 * The rule is therefore: required, no default, and `manual` refused for new writes. The
 * `Manual` case survives only so historical rows still read.
 */
class RequiredProvenanceTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mda = Mda::factory()->create();
    }

    /* ------------------------------------------------------------ required */

    public function test_a_beneficiary_cannot_be_saved_without_a_source(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('without a registration source');

        Beneficiary::create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => 'Amina',
            'last_name' => 'Bello',
        ]);
    }

    public function test_a_household_cannot_be_saved_without_a_source(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('without a registration source');

        Household::create(['owner_mda_id' => $this->mda->id]);
    }

    public function test_nothing_is_persisted_when_the_source_is_missing(): void
    {
        try {
            Beneficiary::create([
                'owner_mda_id' => $this->mda->id,
                'first_name' => 'Amina',
                'last_name' => 'Bello',
            ]);
        } catch (InvalidArgumentException) {
            // expected
        }

        // It fails BEFORE the insert — a half-written record with no lineage would be
        // exactly the thing this rule exists to prevent.
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScopes()->count());
    }

    /* -------------------------------------------------- manual is not assignable */

    public function test_a_new_beneficiary_cannot_claim_the_manual_source(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('manual');

        Beneficiary::create([
            'owner_mda_id' => $this->mda->id,
            'registration_source' => RegistrationSource::Manual,
            'first_name' => 'Amina',
            'last_name' => 'Bello',
        ]);
    }

    public function test_a_new_household_cannot_claim_the_manual_source(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Household::create([
            'owner_mda_id' => $this->mda->id,
            'registration_source' => RegistrationSource::Manual,
        ]);
    }

    public function test_an_existing_record_cannot_be_edited_into_the_manual_source(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id]);

        $this->expectException(InvalidArgumentException::class);

        // A record's origin is a historical fact, not an editable field.
        $beneficiary->update(['registration_source' => RegistrationSource::Manual]);
    }

    public function test_a_bare_string_source_is_validated_too(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // Mass assignment casts strings; the guard must catch the raw value as well as
        // the enum, or the rule could be bypassed by passing 'manual'.
        Beneficiary::create([
            'owner_mda_id' => $this->mda->id,
            'registration_source' => 'manual',
            'first_name' => 'Amina',
            'last_name' => 'Bello',
        ]);
    }

    /* ------------------------------------------------ every real source is allowed */

    public function test_every_assignable_source_can_be_written(): void
    {
        foreach (RegistrationSource::assignable() as $source) {
            $beneficiary = Beneficiary::factory()->create([
                'owner_mda_id' => $this->mda->id,
                'registration_source' => $source,
            ]);

            $this->assertSame($source, $beneficiary->fresh()->registration_source);
        }
    }

    public function test_manual_is_the_only_unassignable_source(): void
    {
        $this->assertSame(
            [RegistrationSource::Manual],
            array_values(array_filter(
                RegistrationSource::cases(),
                static fn (RegistrationSource $case): bool => ! $case->isAssignable(),
            )),
        );

        // Every door named in CLAUDE.md §8 remains available.
        foreach (['excel', 'csv', 'kobo', 'odk', 'api', 'socu', 'government_system'] as $value) {
            $this->assertContains($value, RegistrationSource::assignableValues());
        }
    }

    /* --------------------------------------------------- historical rows survive */

    public function test_a_historical_manual_row_still_reads(): void
    {
        // Written the way a pre-removal row exists in the database, bypassing the model
        // guard exactly as a legacy row does.
        $id = (string) Str::uuid7();
        DB::table('beneficiaries')->insert([
            'id' => $id,
            'owner_mda_id' => $this->mda->id,
            'registration_source' => 'manual',
            'registration_date' => now()->toDateString(),
            'first_name' => 'Historical',
            'last_name' => 'Record',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->findOrFail($id);

        // Removing the enum case outright would make this throw — history has to stay
        // readable, which is why the case is deprecated rather than deleted.
        $this->assertSame(RegistrationSource::Manual, $beneficiary->registration_source);
        $this->assertFalse($beneficiary->registration_source->isAssignable());
    }

    public function test_a_historical_manual_row_can_still_be_edited_otherwise(): void
    {
        $id = (string) Str::uuid7();
        DB::table('beneficiaries')->insert([
            'id' => $id,
            'owner_mda_id' => $this->mda->id,
            'registration_source' => 'manual',
            'registration_date' => now()->toDateString(),
            'first_name' => 'Historical',
            'last_name' => 'Record',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $beneficiary = Beneficiary::query()->withoutGlobalScopes()->findOrFail($id);
        $beneficiary->update(['ward' => 'Ward 4']);

        // Correcting a legacy record must not be blocked by its legacy provenance — the
        // guard only fires when the SOURCE itself is being set.
        $this->assertSame('Ward 4', $beneficiary->fresh()->ward);
        $this->assertSame(RegistrationSource::Manual, $beneficiary->fresh()->registration_source);
    }
}
