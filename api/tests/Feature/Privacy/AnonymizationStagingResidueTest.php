<?php

declare(strict_types=1);

namespace Tests\Feature\Privacy;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Privacy\Services\AnonymizationService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Anonymization must reach the STAGING record, not only the registry row (NFR-PRV-01).
 *
 * Every beneficiary in this system arrived through an import, and the row that created
 * them keeps the payload it was created from — first name, last name, NIN, BVN, phone,
 * address, exactly as the MDA supplied it. Clearing the `beneficiaries` row while that
 * payload survives does not de-identify anybody: the identifiers are still queryable,
 * still joined to the same person by `beneficiary_id`, and still exportable by anyone
 * who can read an import batch.
 *
 * That is the failure mode this file exists for. Erasure that leaves a complete copy one
 * table away is worse than no erasure, because the record now REPORTS itself as
 * anonymized — `anonymized_at` is set, the audit says it happened, and every downstream
 * check believes it.
 */
class AnonymizationStagingResidueTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    /** A committed import row carrying the identity it created a beneficiary from. */
    private function stagedBeneficiary(): array
    {
        $beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'first_name' => 'Ada',
            'last_name' => 'Okoye',
            'nin' => '20000000001',
            'phone' => '08030000001',
        ]);

        $batch = ImportBatch::create([
            'owner_mda_id' => $this->mda->id,
            'original_filename' => 'intake.csv',
            'stored_path' => 'imports/intake.csv',
            'source' => 'csv',
            'status' => 'completed',
        ]);

        $row = ImportRow::query()->create([
            'import_batch_id' => $batch->id,
            'row_number' => 1,
            'payload' => [
                'first_name' => 'Ada',
                'last_name' => 'Okoye',
                'nin' => '20000000001',
                'phone' => '08030000001',
                'address' => '5 Market Road',
                'lga' => 'dutse',
            ],
            'is_valid' => true,
            'match_candidates' => [],
            'beneficiary_id' => $beneficiary->id,
        ]);

        return [$beneficiary, $row];
    }

    private function anonymize(Beneficiary $beneficiary, bool $keepQuasi = false): void
    {
        app(AnonymizationService::class)->anonymize($beneficiary, $keepQuasi, 'test_policy', $this->officer);
    }

    /* --------------------------------------------------------------- the residue */

    public function test_anonymizing_clears_the_identity_from_its_import_row(): void
    {
        [$beneficiary, $row] = $this->stagedBeneficiary();

        $this->anonymize($beneficiary);

        $payload = $row->fresh()->payload ?? [];
        $flat = json_encode($payload) ?: '';

        $this->assertStringNotContainsString('20000000001', $flat, 'The NIN survived in the import row.');
        $this->assertStringNotContainsString('Okoye', $flat, 'The name survived in the import row.');
        $this->assertStringNotContainsString('08030000001', $flat, 'The phone survived in the import row.');
        $this->assertStringNotContainsString('5 Market Road', $flat, 'The address survived in the import row.');
    }

    public function test_no_table_still_holds_the_erased_identifier(): void
    {
        // The broad sweep. Anywhere the NIN is still readable, the erasure is incomplete —
        // this catches a new table that starts carrying payloads without anyone
        // remembering that anonymization has to reach it.
        [$beneficiary] = $this->stagedBeneficiary();

        $this->anonymize($beneficiary);

        foreach (['import_rows', 'beneficiaries'] as $table) {
            $rows = DB::table($table)->get();
            $this->assertStringNotContainsString(
                '20000000001',
                json_encode($rows) ?: '',
                "The erased NIN is still readable in `{$table}`.",
            );
        }
    }

    /* ------------------------------------------------- what must NOT be destroyed */

    public function test_the_import_row_survives_so_provenance_is_not_rewritten(): void
    {
        // The row is redacted, never deleted. It records that this batch produced this
        // record; destroying it would erase the provenance the registry depends on and
        // silently change every import tally that was already reported.
        [$beneficiary, $row] = $this->stagedBeneficiary();
        $batchId = $row->import_batch_id;

        $this->anonymize($beneficiary);

        $fresh = $row->fresh();
        $this->assertNotNull($fresh, 'The staging row must survive; only its payload is cleared.');
        $this->assertSame($batchId, $fresh->import_batch_id);
        $this->assertSame($beneficiary->id, $fresh->beneficiary_id);
        $this->assertSame(1, $fresh->row_number);
    }

    public function test_another_persons_row_in_the_same_batch_is_untouched(): void
    {
        // Redaction is per person. A batch holds many people, and anonymizing one must
        // not quietly erase the rest of the file.
        [$beneficiary, $row] = $this->stagedBeneficiary();

        $neighbour = ImportRow::query()->create([
            'import_batch_id' => $row->import_batch_id,
            'row_number' => 2,
            'payload' => ['first_name' => 'Musa', 'last_name' => 'Danjuma', 'nin' => '20000000002'],
            'is_valid' => true,
            'match_candidates' => [],
            'beneficiary_id' => Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id])->id,
        ]);

        $this->anonymize($beneficiary);

        $this->assertStringContainsString('20000000002', json_encode($neighbour->fresh()->payload) ?: '');
    }

    public function test_aggregate_mode_also_clears_the_staged_direct_identifiers(): void
    {
        // `aggregate` keeps quasi fields on the registry row for statistics. That is a
        // decision about the REGISTRY; the staged copy of the raw file has no
        // statistical role, so its direct identifiers go either way.
        [$beneficiary, $row] = $this->stagedBeneficiary();

        $this->anonymize($beneficiary, keepQuasi: true);

        $flat = json_encode($row->fresh()->payload) ?: '';
        $this->assertStringNotContainsString('20000000001', $flat);
        $this->assertStringNotContainsString('Okoye', $flat);
    }
}
