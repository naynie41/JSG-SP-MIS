<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\ImportBatch;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Detecting an import that is not being worked on.
 *
 * A batch waiting on a dead queue worker produces NO error — nothing failed, the job was
 * simply never picked up — so the UI would otherwise say "Processing…" forever. These
 * tests pin the one signal that distinguishes "working" from "nobody is listening".
 */
class ImportStalledDetectionTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create();
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function batch(ImportStatus $status, int $ageSeconds = 0): ImportBatch
    {
        $batch = ImportBatch::query()->create([
            'owner_mda_id' => $this->mda->id,
            'uploaded_by' => $this->officer->id,
            'original_filename' => 'people.csv',
            'stored_path' => 'imports/people.csv',
            'source' => RegistrationSource::Excel,
            'status' => $status,
        ]);

        // Backdate the last sign of life. `updated_at` is what staleness measures from,
        // so it has to be set directly rather than by touching the model.
        if ($ageSeconds > 0) {
            $at = Carbon::now()->subSeconds($ageSeconds);
            ImportBatch::query()->withoutGlobalScopes()->where('id', $batch->id)
                ->update(['created_at' => $at, 'updated_at' => $at]);
        }

        return $batch->fresh();
    }

    // ------------------------------------------------------------- the signal

    public function test_a_batch_that_just_arrived_is_not_stalled(): void
    {
        $batch = $this->batch(ImportStatus::Pending);

        $this->assertFalse($batch->processingLooksStalled());
        $this->assertNotNull($batch->processingForSeconds());
        $this->assertLessThan(5, $batch->processingForSeconds());
    }

    public function test_a_batch_waiting_past_the_threshold_is_stalled(): void
    {
        $batch = $this->batch(ImportStatus::Pending, ageSeconds: 600);

        $this->assertTrue($batch->processingLooksStalled());
        $this->assertGreaterThanOrEqual(600, $batch->processingForSeconds());
    }

    public function test_the_threshold_is_configuration_not_a_hard_coded_number(): void
    {
        $batch = $this->batch(ImportStatus::Pending, ageSeconds: 120);
        $this->assertTrue($batch->processingLooksStalled());

        config(['registry.import.stalled_after_seconds' => 3600]);
        $this->assertFalse($batch->fresh()->processingLooksStalled());
    }

    public function test_every_queued_status_is_watched(): void
    {
        // Committing can hang for exactly the same reason parsing can.
        foreach ([ImportStatus::Pending, ImportStatus::Processing, ImportStatus::Committing] as $status) {
            $this->assertTrue(
                $this->batch($status, ageSeconds: 600)->processingLooksStalled(),
                "{$status->value} should be watched for staleness",
            );
        }
    }

    public function test_a_settled_batch_is_never_stalled_however_old(): void
    {
        // An old completed/failed/preview batch is not waiting on anything — flagging it
        // would train people to ignore the warning.
        foreach ([ImportStatus::PreviewReady, ImportStatus::Completed, ImportStatus::Failed] as $status) {
            $batch = $this->batch($status, ageSeconds: 86_400);

            $this->assertFalse($batch->processingLooksStalled(), "{$status->value} must not be stalled");
            $this->assertNull($batch->processingForSeconds(), "{$status->value} is not waiting on the queue");
        }
    }

    public function test_a_batch_awaiting_a_human_mapping_is_not_stalled(): void
    {
        // MappingRequired is waiting on a PERSON, not the queue. Telling them the worker
        // is broken would send them chasing the wrong thing.
        $batch = $this->batch(ImportStatus::MappingRequired, ageSeconds: 86_400);

        $this->assertFalse($batch->processingLooksStalled());
        $this->assertNull($batch->processingForSeconds());
    }

    public function test_progress_resets_the_clock(): void
    {
        // A worker that is alive touches the row as it advances, so a long-running but
        // progressing import must not be reported as stalled.
        $batch = $this->batch(ImportStatus::Processing, ageSeconds: 600);
        $this->assertTrue($batch->processingLooksStalled());

        $batch->touch();

        $this->assertFalse($batch->fresh()->processingLooksStalled());
    }

    // ------------------------------------------------------------- the API

    public function test_the_api_reports_staleness_to_the_client(): void
    {
        $batch = $this->batch(ImportStatus::Pending, ageSeconds: 600);

        $this->actingAs($this->officer)
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.processing_stalled', true)
            ->assertJsonPath('data.status', 'pending');

        $seconds = $this->getJson("/api/v1/beneficiaries/imports/{$batch->id}")->json('data.processing_for_seconds');
        $this->assertGreaterThanOrEqual(600, $seconds);
    }

    public function test_the_api_reports_a_healthy_batch_as_not_stalled(): void
    {
        $batch = $this->batch(ImportStatus::Pending);

        $this->actingAs($this->officer)
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.processing_stalled', false);
    }

    public function test_staleness_is_null_for_a_settled_batch_in_the_api(): void
    {
        $batch = $this->batch(ImportStatus::PreviewReady, ageSeconds: 86_400);

        $this->actingAs($this->officer)
            ->getJson("/api/v1/beneficiaries/imports/{$batch->id}")
            ->assertOk()
            ->assertJsonPath('data.processing_for_seconds', null)
            ->assertJsonPath('data.processing_stalled', false);
    }
}
