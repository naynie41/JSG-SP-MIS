<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Jobs\CommitImportBatch;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Tests\TestCase;

/**
 * Bulk Excel/CSV import (PRD FR-REG-02/06): queued parse → preview with row-level
 * errors → confirm → commit-only-valid, with provenance, batch tracking, audit,
 * and idempotency. Runs on the Phase 1 sync-queue test config.
 */
class BulkImportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['partnerA'] = $this->user($this->mdaA, RoleKey::DevelopmentPartner);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function tokenFor(string $key): string
    {
        return $this->users[$key]->createToken('test')->plainTextToken;
    }

    /**
     * A file with three persistable rows and one rejected row. Row 3 has malformed
     * IDENTITY fields (missing last_name, bad NIN) → rejected in whole (FR-REG-05).
     * Row 4's only failure is a future DOB, a NON-IDENTITY field → the field is
     * dropped and the row is still saved (FR-REG-09).
     */
    private function mixedCsv(): UploadedFile
    {
        $csv = implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward,original_record_id',
            'Amina,Sadiq,,,08030000001,1990-01-01,female,dutse,Ward 1,EXT-1',
            'Musa,Bello,22200000011,,08030000002,1985-06-15,male,Kazaure,Ward 2,EXT-2',
            'Bad,,123,,,,martian,not_a_lga, ,EXT-3',            // IDENTITY malformed → reject
            'Fatima,Sule,,,,3000-01-01,female,gumel,Ward 3,EXT-4', // future DOB (non-identity) → kept, DOB dropped
        ]);

        return UploadedFile::fake()->createWithContent('beneficiaries.csv', $csv);
    }

    private function upload(string $userKey, UploadedFile $file): ImportBatch
    {
        $response = $this->withToken($this->tokenFor($userKey))
            ->post('/api/v1/beneficiaries/imports', ['file' => $file], ['Accept' => 'application/json'])
            ->assertCreated();

        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
    }

    public function test_upload_produces_a_preview_and_persists_nothing(): void
    {
        $batch = $this->upload('officerA', $this->mixedCsv());

        // Parsing ran on the (sync) queue → preview is ready with a summary.
        $this->assertSame(ImportStatus::PreviewReady, $batch->status);
        $this->assertSame(4, $batch->total_rows);
        // Rows 1, 2 and 4 are persistable (row 4 keeps, with its DOB dropped); only
        // the identity-malformed row 3 is rejected.
        $this->assertSame(3, $batch->valid_rows);
        $this->assertSame(1, $batch->invalid_rows);
        $this->assertSame(1, $batch->rejected_rows);       // identity malformed
        $this->assertSame(1, $batch->dropped_field_rows);  // future DOB dropped

        // Nothing committed before confirmation.
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());

        // The preview exposes the two error groups distinctly.
        $this->app['auth']->forgetGuards();
        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.summary.valid_rows', 3)
            ->assertJsonPath('data.summary.rejected_rows', 1)
            ->assertJsonPath('data.summary.dropped_field_rows', 1)
            ->assertJsonCount(4, 'data.rows')
            // Row 3: rejected on an identity field.
            ->assertJsonPath('data.rows.2.is_valid', false)
            ->assertJsonFragment(['field' => 'last_name', 'group' => 'identity'])
            // Row 4: kept, but the bad DOB is reported as a dropped non-identity field.
            ->assertJsonPath('data.rows.3.is_valid', true)
            ->assertJsonFragment(['field' => 'date_of_birth', 'group' => 'dropped']);
    }

    public function test_import_forms_households_from_the_source_reference(): void
    {
        // Two rows sharing a household key; the first is flagged as head.
        $csv = implode("\n", [
            'first_name,last_name,date_of_birth,gender,lga,ward,original_record_id,household_id,household_role,household_head',
            'Amina,Sadiq,1990-01-01,female,dutse,Ward 1,R1,HH-1,head,yes',
            'Musa,Sadiq,2015-02-02,male,dutse,Ward 1,R2,HH-1,child,no',
        ]);

        $batch = $this->upload('officerA', UploadedFile::fake()->createWithContent('household.csv', $csv));
        $this->assertSame(2, $batch->valid_rows);
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        // Exactly one household was formed, owned + provenanced correctly.
        $household = Household::query()->withoutGlobalScope(MdaScope::class)->get();
        $this->assertCount(1, $household);
        $formed = $household->first();
        $this->assertSame($this->mdaA->id, $formed->owner_mda_id);
        $this->assertSame(RegistrationSource::Csv, $formed->registration_source);
        $this->assertSame('HH-1', $formed->original_record_id);
        $this->assertSame($batch->id, $formed->import_batch_id);

        // Both beneficiaries have an open membership; the flagged one is head.
        $memberships = HouseholdMembership::query()->where('household_id', $formed->id)->get();
        $this->assertCount(2, $memberships);
        $this->assertSame(2, $memberships->whereNull('left_at')->count());
        $head = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($formed->head_beneficiary_id);
        $this->assertSame('Amina', $head->first_name);

        // Re-importing the same source does not create a second household or member.
        $this->app['auth']->forgetGuards();
        $batch2 = $this->upload('officerA', UploadedFile::fake()->createWithContent('household.csv', $csv));
        $this->app['auth']->forgetGuards();
        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch2->id}/confirm")
            ->assertOk();

        $this->assertSame(1, Household::query()->withoutGlobalScope(MdaScope::class)->count());
        $this->assertSame(2, HouseholdMembership::query()->count());
    }

    public function test_confirm_commits_only_valid_rows_with_provenance_and_audit(): void
    {
        $batch = $this->upload('officerA', $this->mixedCsv());
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        $batch->refresh();
        $this->assertSame(ImportStatus::Completed, $batch->status);
        $this->assertSame(3, $batch->committed_rows);

        $beneficiaries = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->get();
        $this->assertCount(3, $beneficiaries);

        // Correct ownership + provenance on every committed record.
        foreach ($beneficiaries as $beneficiary) {
            $this->assertSame($this->mdaA->id, $beneficiary->owner_mda_id);
            $this->assertSame(RegistrationSource::Csv, $beneficiary->registration_source);
            $this->assertSame($batch->id, $beneficiary->import_batch_id);
            $this->assertNotNull($beneficiary->original_record_id);
        }

        $this->assertDatabaseHas('beneficiaries', [
            'import_batch_id' => $batch->id,
            'original_record_id' => 'EXT-1',
            'registration_source' => RegistrationSource::Csv->value,
        ]);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.created']);

        // The kept row was saved with its malformed non-identity field dropped to
        // null (FR-REG-09) — the row persists, the bad value does not.
        $kept = Beneficiary::query()->withoutGlobalScope(MdaScope::class)
            ->where('original_record_id', 'EXT-4')->firstOrFail();
        $this->assertSame('Fatima', $kept->first_name);
        $this->assertNull($kept->date_of_birth);

        // The identity-rejected row never reached the live table.
        $this->assertDatabaseMissing('beneficiaries', ['original_record_id' => 'EXT-3']);

        // Rejected rows are retained in staging (never silently dropped), but stay
        // out of the live tables.
        $this->assertSame(1, $batch->rows()->where('is_valid', false)->count());
    }

    public function test_recommitting_a_batch_does_not_double_insert(): void
    {
        $batch = $this->upload('officerA', $this->mixedCsv());
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        $this->assertSame(3, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());

        // Simulate a retry mid-commit: force the batch back to committing and
        // re-run the job. Rows already stamped with beneficiary_id are skipped.
        $batch->update(['status' => ImportStatus::Committing]);
        CommitImportBatch::dispatchSync($batch->id, (string) $this->users['officerA']->id);

        $this->assertSame(3, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());
    }

    public function test_validation_splits_identity_reject_from_non_identity_drop(): void
    {
        // Row A: valid, no NIN/BVN at all → absent optional identifiers stay valid.
        // Row B: a PRESENT but malformed NIN → the whole row is rejected (identity).
        // Row C: valid identity, but a bad LGA, bad gender and future DOB → those
        //        non-identity fields are dropped and the row is still saved.
        $csv = implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward,original_record_id',
            'Halima,Yusuf,,,08030000010,1988-04-04,female,dutse,Ward 1,A-1',
            'Sani,Ibrahim,123,,08030000011,1990-01-01,male,dutse,Ward 1,B-1',
            'Rabi,Idris,,,,3000-01-01,alien,not_a_lga,Ward 2,C-1',
        ]);

        $batch = $this->upload('officerA', UploadedFile::fake()->createWithContent('groups.csv', $csv));

        $this->assertSame(2, $batch->valid_rows);          // A + C persistable
        $this->assertSame(1, $batch->invalid_rows);        // B rejected
        $this->assertSame(1, $batch->rejected_rows);       // identity malformed (B)
        $this->assertSame(1, $batch->dropped_field_rows);  // non-identity dropped (C)

        $this->app['auth']->forgetGuards();
        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.rows.0.is_valid', true)   // absent optional NIN/BVN ok
            ->assertJsonPath('data.rows.1.is_valid', false)  // present malformed NIN
            ->assertJsonFragment(['field' => 'nin', 'group' => 'identity'])
            ->assertJsonPath('data.rows.2.is_valid', true)   // kept despite bad non-identity
            ->assertJsonFragment(['field' => 'lga', 'group' => 'dropped'])
            ->assertJsonFragment(['field' => 'gender', 'group' => 'dropped'])
            ->assertJsonFragment(['field' => 'date_of_birth', 'group' => 'dropped']);

        $this->app['auth']->forgetGuards();
        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        // The rejected row's PII never reaches the live table.
        $this->assertDatabaseMissing('beneficiaries', ['original_record_id' => 'B-1']);

        // The kept row saved, with each malformed non-identity field nulled.
        $kept = Beneficiary::query()->withoutGlobalScope(MdaScope::class)
            ->where('original_record_id', 'C-1')->firstOrFail();
        $this->assertNull($kept->lga);
        $this->assertNull($kept->gender);
        $this->assertNull($kept->date_of_birth);

        // Only the two persistable rows were committed.
        $this->assertSame(2, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());
    }

    public function test_excel_xlsx_files_are_parsed(): void
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['first_name', 'last_name', 'nin', 'date_of_birth', 'gender', 'lga', 'ward', 'original_record_id'],
            ['Zainab', 'Umar', '22200000099', '1992-03-03', 'female', 'hadejia', 'Ward 5', 'X-1'],
        ]);

        $path = tempnam(sys_get_temp_dir(), 'imp').'.xlsx';
        (new XlsxWriter($spreadsheet))->save($path);
        $file = new UploadedFile($path, 'beneficiaries.xlsx', null, null, true);

        $batch = $this->upload('officerA', $file);

        $this->assertSame(ImportStatus::PreviewReady, $batch->status);
        $this->assertSame(1, $batch->total_rows);
        $this->assertSame(1, $batch->valid_rows);
        $this->assertSame(RegistrationSource::Excel, $batch->source);
    }

    public function test_permissions_and_scoping_are_enforced(): void
    {
        // No beneficiary.create → cannot upload.
        $this->withToken($this->tokenFor('partnerA'))
            ->post('/api/v1/beneficiaries/imports', ['file' => $this->mixedCsv()], ['Accept' => 'application/json'])
            ->assertStatus(403);

        $this->app['auth']->forgetGuards();

        $batch = $this->upload('officerA', $this->mixedCsv());
        $this->app['auth']->forgetGuards();

        // Another MDA cannot see the batch (owner-scoped → 404).
        $this->withToken($this->tokenFor('officerB'))
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertStatus(404);

        $this->app['auth']->forgetGuards();

        // ...nor confirm it (owner-only → 403).
        $this->withToken($this->tokenFor('officerB'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertStatus(403);
    }
}
