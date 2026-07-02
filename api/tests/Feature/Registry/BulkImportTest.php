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

    /** A file with two valid rows and two invalid rows. */
    private function mixedCsv(): UploadedFile
    {
        $csv = implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward,original_record_id',
            'Amina,Sadiq,,,08030000001,1990-01-01,female,dutse,Ward 1,EXT-1',
            'Musa,Bello,22200000011,,08030000002,1985-06-15,male,Kazaure,Ward 2,EXT-2',
            'Bad,,123,,,,martian,not_a_lga, ,EXT-3',            // missing last_name, bad nin/gender/lga/dob
            'Fatima,Sule,,,,3000-01-01,female,gumel,Ward 3,EXT-4', // future DOB
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
        $this->assertSame(2, $batch->valid_rows);
        $this->assertSame(2, $batch->invalid_rows);

        // Nothing committed before confirmation.
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());

        // The preview exposes row-level errors.
        $this->app['auth']->forgetGuards();
        $this->withToken($this->tokenFor('officerA'))
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.summary.valid_rows', 2)
            ->assertJsonCount(4, 'data.rows')
            ->assertJsonPath('data.rows.2.is_valid', false)
            ->assertJsonFragment(['field' => 'last_name']);
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
        $this->assertSame(2, $batch->committed_rows);

        $beneficiaries = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->get();
        $this->assertCount(2, $beneficiaries);

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

        // Invalid rows are retained (never silently dropped).
        $this->assertSame(2, $batch->rows()->where('is_valid', false)->count());
    }

    public function test_recommitting_a_batch_does_not_double_insert(): void
    {
        $batch = $this->upload('officerA', $this->mixedCsv());
        $this->app['auth']->forgetGuards();

        $this->withToken($this->tokenFor('officerA'))
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        $this->assertSame(2, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());

        // Simulate a retry mid-commit: force the batch back to committing and
        // re-run the job. Rows already stamped with beneficiary_id are skipped.
        $batch->update(['status' => ImportStatus::Committing]);
        CommitImportBatch::dispatchSync($batch->id, (string) $this->users['officerA']->id);

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
