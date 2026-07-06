<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Kobo/ODK ingestion through the shared import pipeline (PRD FR-REG-02): the
 * source adapter maps foreign column names onto the canonical schema, provenance
 * and original_record_id are stamped, and validation/row-errors are shared with
 * Excel/CSV import.
 */
class HybridSourceImportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private User $officer;

    private Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);
        $this->activity = Activity::factory()->forProgramme(
            Programme::factory()->individual()->create(['owner_mda_id' => $this->mda->id]),
        )->create();
    }

    private function token(): string
    {
        return $this->officer->createToken('test')->plainTextToken;
    }

    private function upload(UploadedFile $file, string $source): ImportBatch
    {
        $response = $this->withToken($this->token())
            ->post('/api/v1/beneficiaries/imports', ['file' => $file, 'source' => $source, 'activity_id' => $this->activity->id], ['Accept' => 'application/json'])
            ->assertCreated();

        return ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id'));
    }

    public function test_kobo_export_is_mapped_and_committed_with_provenance(): void
    {
        // Kobo columns: group-prefixed question names + `sex`/`dob` aliases + `_id`.
        $csv = implode("\n", [
            'personal/first_name,personal/last_name,nin,sex,dob,lga,ward,phone,_id',
            'Amina,Yusuf,22200000011,female,1990-01-01,dutse,Ward 1,08030000001,KOBO-1',
            'Bad,,123,martian,,not_a_lga,,,KOBO-2', // invalid: missing/last_name, bad nin/gender/dob/lga/ward
        ]);
        $batch = $this->upload(UploadedFile::fake()->createWithContent('kobo.csv', $csv), 'kobo');

        $this->assertSame(ImportStatus::PreviewReady, $batch->status);
        $this->assertSame(RegistrationSource::Kobo, $batch->source);
        $this->assertSame(2, $batch->total_rows);
        $this->assertSame(1, $batch->valid_rows);
        $this->assertSame(1, $batch->invalid_rows);
        $this->app['auth']->forgetGuards();

        $this->withToken($this->token())
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->firstOrFail();
        $this->assertSame('Amina', $beneficiary->first_name);
        $this->assertSame('Yusuf', $beneficiary->last_name);
        $this->assertSame($this->mda->id, $beneficiary->owner_mda_id);
        $this->assertSame(RegistrationSource::Kobo, $beneficiary->registration_source);
        $this->assertSame('KOBO-1', $beneficiary->original_record_id);
        $this->assertSame($batch->id, $beneficiary->import_batch_id);

        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.created']);
    }

    public function test_odk_export_is_mapped_and_committed_with_provenance(): void
    {
        $csv = implode("\n", [
            'first_name,last_name,nin,gender,date_of_birth,lga,ward,instanceID',
            'Musa,Bello,22200000022,male,1985-06-15,kazaure,Ward 2,uuid:odk-xyz',
        ]);
        $batch = $this->upload(UploadedFile::fake()->createWithContent('odk.csv', $csv), 'odk');

        $this->assertSame(RegistrationSource::Odk, $batch->source);
        $this->assertSame(1, $batch->valid_rows);
        $this->app['auth']->forgetGuards();

        $this->withToken($this->token())
            ->postJson("/api/v1/beneficiaries/imports/{$batch->id}/confirm")
            ->assertOk();

        $this->assertDatabaseHas('beneficiaries', [
            'owner_mda_id' => $this->mda->id,
            'registration_source' => RegistrationSource::Odk->value,
            'original_record_id' => 'uuid:odk-xyz',
            'last_name' => 'Bello',
        ]);
    }

    public function test_kobo_bad_rows_are_flagged_in_the_preview(): void
    {
        $csv = implode("\n", [
            'personal/first_name,personal/last_name,nin,sex,dob,lga,ward,_id',
            'Bad,,123,martian,,not_a_lga,,KOBO-9',
        ]);
        $batch = $this->upload(UploadedFile::fake()->createWithContent('kobo.csv', $csv), 'kobo');

        // Nothing committed; the invalid row carries structured errors.
        $this->assertSame(0, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count());

        $this->app['auth']->forgetGuards();
        $this->withToken($this->token())
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.summary.invalid_rows', 1)
            ->assertJsonPath('data.rows.0.is_valid', false)
            ->assertJsonFragment(['field' => 'last_name'])
            ->assertJsonFragment(['field' => 'gender']);
    }
}
