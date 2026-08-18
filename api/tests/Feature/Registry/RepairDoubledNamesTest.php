<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Repairing names doubled by imports made before the full-name split existed.
 *
 * This command writes to real beneficiary records, so the tests care most about what it
 * REFUSES to touch, and about the derived key that a careless bulk update would leave
 * stale.
 */
class RepairDoubledNamesTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mda = Mda::factory()->create();
    }

    private function beneficiary(string $first, string $last): Beneficiary
    {
        return Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => $first,
            'last_name' => $last,
            'date_of_birth' => '1990-01-01',
        ]);
    }

    public function test_a_doubled_name_is_re_split_by_the_import_rule(): void
    {
        $two = $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');
        $three = $this->beneficiary('Barira Sadau Barde', 'Barira Sadau Barde');

        $this->artisan('registry:repair-doubled-names --apply')->assertExitCode(0);

        $this->assertSame(['Rekiya', 'Bagwai'], [$two->fresh()->first_name, $two->fresh()->last_name]);
        // Three names: the middle token belongs to the surname, as on import.
        $this->assertSame(['Barira', 'Sadau Barde'], [$three->fresh()->first_name, $three->fresh()->last_name]);
    }

    public function test_it_reports_without_writing_unless_apply_is_given(): void
    {
        // A repair that writes by default is a repair someone runs by accident.
        $b = $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');

        $this->artisan('registry:repair-doubled-names')->assertExitCode(0);

        $this->assertSame('Rekiya Bagwai', $b->fresh()->first_name);
    }

    public function test_it_refreshes_the_fuzzy_blocking_key(): void
    {
        // `block_name_dob` is phonetic(last_name)|dob_year (FR-DUP-03). A bulk query
        // update would leave it keyed on the OLD surname, and duplicate detection would
        // quietly stop finding these people.
        $b = $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');
        $before = $b->fresh()->block_name_dob;

        $this->artisan('registry:repair-doubled-names --apply');

        $fresh = $b->fresh();
        $this->assertNotSame($before, $fresh->block_name_dob);
        $this->assertSame(
            Beneficiary::blockNameDobFor('Bagwai', '1990-01-01'),
            $fresh->block_name_dob,
        );
    }

    public function test_a_genuine_repeated_single_name_is_left_alone(): void
    {
        // Someone actually called "Musa Musa" is not a doubled name — there is no space
        // inside either field, so there is nothing to re-split and no way to tell it
        // apart from a real repetition. It must not be touched.
        $b = $this->beneficiary('Musa', 'Musa');

        // Not merely left unwritten — not even CONSIDERED. It must not show up as an
        // "unsplittable" leftover in the report, because there is nothing wrong with it.
        $this->artisan('registry:repair-doubled-names --apply')
            ->expectsOutputToContain('No doubled names found.')
            ->doesntExpectOutputToContain('unsplittable');

        $this->assertSame(['Musa', 'Musa'], [$b->fresh()->first_name, $b->fresh()->last_name]);
    }

    public function test_correctly_split_records_are_untouched(): void
    {
        $b = $this->beneficiary('Ada', 'Okoye');
        $before = $b->fresh()->updated_at;

        $this->artisan('registry:repair-doubled-names --apply');

        $this->assertSame(['Ada', 'Okoye'], [$b->fresh()->first_name, $b->fresh()->last_name]);
        $this->assertEquals($before, $b->fresh()->updated_at, 'an untouched record must not be re-saved');
    }

    public function test_it_is_idempotent(): void
    {
        $b = $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');

        $this->artisan('registry:repair-doubled-names --apply');
        $after = $b->fresh()->updated_at;

        $this->artisan('registry:repair-doubled-names --apply')
            ->expectsOutputToContain('No doubled names found.');

        $this->assertEquals($after, $b->fresh()->updated_at);
    }

    public function test_it_can_be_limited_to_one_import_batch(): void
    {
        // A targeted repair matters when only ONE import was mapped badly — repairing
        // the whole registry to fix one batch touches records nobody asked about.
        $batch = ImportBatch::query()->create([
            'owner_mda_id' => $this->mda->id,
            'original_filename' => 'bad-mapping.csv',
            'stored_path' => 'imports/bad-mapping.csv',
            'source' => RegistrationSource::Excel,
            'status' => ImportStatus::Completed,
        ]);

        $inBatch = $this->beneficiary('Barira Sadau Barde', 'Barira Sadau Barde');
        $elsewhere = $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');

        DB::table('beneficiaries')->where('id', $inBatch->id)->update(['import_batch_id' => $batch->id]);
        DB::table('beneficiaries')->where('id', $elsewhere->id)->update(['import_batch_id' => null]);

        $this->artisan("registry:repair-doubled-names --apply --batch={$batch->id}");

        $this->assertSame('Barira', $inBatch->fresh()->first_name);
        $this->assertSame('Rekiya Bagwai', $elsewhere->fresh()->first_name, 'outside the named batch');
    }

    public function test_the_repair_is_audited_without_recording_any_name(): void
    {
        $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');

        $this->artisan('registry:repair-doubled-names --apply');

        $entry = DB::table('audit_log')->where('action', 'registry.doubled_names_repaired')->firstOrFail();
        $after = (string) $entry->after;

        $this->assertStringContainsString('"repaired":1', $after);
        // Names are PII (CLAUDE.md §8). The per-record entries carry the before/after;
        // this summary must not duplicate them into a second place.
        $this->assertStringNotContainsString('Rekiya', $after);
        $this->assertStringNotContainsString('Bagwai', $after);
    }

    public function test_the_console_output_never_prints_a_name(): void
    {
        $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');

        $this->artisan('registry:repair-doubled-names')
            ->doesntExpectOutputToContain('Rekiya')
            ->doesntExpectOutputToContain('Bagwai')
            // The shape is what an operator needs to check the rule, and carries no PII.
            ->expectsOutputToContain('2-token name')
            ->assertExitCode(0);
    }

    public function test_each_repaired_record_is_audited_individually(): void
    {
        $b = $this->beneficiary('Rekiya Bagwai', 'Rekiya Bagwai');

        $this->artisan('registry:repair-doubled-names --apply');

        // Saved through the model, so the normal beneficiary audit trail applies —
        // the change is attributable and reversible from the log.
        $this->assertDatabaseHas('audit_log', [
            'action' => 'beneficiary.updated',
            'entity_id' => $b->id,
        ]);
    }
}
