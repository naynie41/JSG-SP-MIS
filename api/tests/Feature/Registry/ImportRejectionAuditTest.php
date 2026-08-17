<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Identity-level import rejections reach the AUDIT TRAIL, not just the batch's error
 * report (SECURITY.md §6, PRD v1.2): "who, when, batch, row count rejected".
 *
 * The "who" is the interesting part. Parsing runs on the queue, where `Auth::user()` is
 * null, so a model-level audit written from inside the job has no actor — the entry
 * would say a batch was rejected but not who submitted it. The uploader is therefore
 * stamped explicitly from `import_batches.uploaded_by`.
 *
 * Rejections are the one import outcome that discards citizen data, so an auditor has
 * to be able to answer "who submitted a file we threw rows away from" by filtering on
 * actor, without joining back through the batch.
 */
class ImportRejectionAuditTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

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
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $programme = Programme::factory()->individual()->create();
        $this->activity = Activity::factory()->forProgramme($programme, $this->mda)->create();
    }

    private function upload(string $csv): ImportBatch
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)
            ->post(
                '/api/v1/beneficiaries/imports',
                ['file' => UploadedFile::fake()->createWithContent('beneficiaries.csv', $csv), 'activity_id' => $this->activity->id],
                ['Accept' => 'application/json'],
            )
            ->assertCreated();

        $this->app['auth']->forgetGuards();

        return $this->confirmImportMapping(ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($response->json('data.id')));
    }

    private const HEADER = 'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward,original_record_id';

    /** Two rows rejected on identity fields, one clean. */
    private function csvWithRejections(): string
    {
        return implode("\n", [
            self::HEADER,
            'Amina,Sadiq,,,08030000001,1990-01-01,female,dutse,Ward 1,EXT-1',
            'Bad,,123,,,,martian,not_a_lga, ,EXT-2',
            'Worse,,456,,,,martian,not_a_lga, ,EXT-3',
        ]);
    }

    private function rejectionEntry(ImportBatch $batch): ?AuditLog
    {
        return AuditLog::query()
            ->where('action', 'import.rows_rejected')
            ->where('entity_id', $batch->id)
            ->first();
    }

    /* ------------------------------------------------------------------ the entry */

    public function test_an_identity_rejection_is_written_to_the_audit_trail(): void
    {
        $batch = $this->upload($this->csvWithRejections());

        $this->assertSame(2, $batch->rejected_rows);

        $entry = $this->rejectionEntry($batch);
        $this->assertNotNull($entry, 'identity rejections must reach the audit trail, not only the error report');
        $this->assertSame('import_batch', $entry->entity_type);
        $this->assertNotNull($entry->created_at, 'when');
    }

    public function test_the_entry_names_the_uploader_as_the_actor(): void
    {
        $batch = $this->upload($this->csvWithRejections());
        $entry = $this->rejectionEntry($batch);

        // The whole point: parsing runs on the queue, so without an explicit actor this
        // would be null and the trail would not say WHO submitted the file.
        $this->assertSame($this->officer->id, $entry?->actor_id);
        $this->assertSame($this->mda->id, $entry?->actor_mda_id);
    }

    public function test_the_entry_carries_the_rejected_row_count_and_the_batch(): void
    {
        $batch = $this->upload($this->csvWithRejections());
        $after = $this->rejectionEntry($batch)?->after;

        $this->assertSame(2, $after['rejected_rows'] ?? null);
        $this->assertSame(3, $after['total_rows'] ?? null);
        $this->assertSame($batch->id, $after['import_batch_id'] ?? null);
    }

    /* --------------------------------------------------------------- no rejections */

    public function test_a_clean_file_writes_no_rejection_entry(): void
    {
        $batch = $this->upload(implode("\n", [
            self::HEADER,
            'Amina,Sadiq,,,08030000001,1990-01-01,female,dutse,Ward 1,EXT-1',
        ]));

        $this->assertSame(0, $batch->rejected_rows);
        $this->assertNull($this->rejectionEntry($batch), 'no rejections means no rejection entry — the trail is not padded');
    }

    /* --------------------------------------------------------------------- no PII */

    public function test_the_entry_holds_counts_only_and_never_the_rejected_values(): void
    {
        $batch = $this->upload($this->csvWithRejections());
        $after = $this->rejectionEntry($batch)?->after ?? [];

        /*
         * Asserted as an exact KEY SET rather than by scanning the payload for
         * identifier-shaped text. A digit-run regex over the whole payload matches the
         * batch UUID whenever its hex happens to be all digits — a test that passes or
         * fails on the luck of a generated id. Pinning the keys is both deterministic
         * and stronger: a new field cannot be added here without this failing.
         */
        $this->assertSame(
            ['import_batch_id', 'reason', 'rejected_rows', 'total_rows'],
            collect(array_keys($after))->sort()->values()->all(),
        );

        /*
         * A rejected row is exactly the data we refused to store; auditing it would
         * reintroduce that PII through the back door (SECURITY.md §6).
         *
         * The batch id is excluded from the scan rather than searched: it is a UUID, and
         * short numeric needles like "123" occur inside random hex often enough to make
         * the assertion pass or fail on the luck of a generated id. Its value is checked
         * exactly, above.
         */
        $payload = (string) json_encode(Arr::except($after, ['import_batch_id']));
        foreach (['Bad', 'Worse', 'not_a_lga', '123', '456'] as $value) {
            $this->assertStringNotContainsString($value, $payload);
        }
    }
}
