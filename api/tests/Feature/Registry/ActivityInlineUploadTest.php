<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Activity-creation wizard's OPTIONAL inline upload (PRD §10): dedup runs in PREVIEW
 * before anything is saved; on confirm the activity + new beneficiaries + interventions
 * are created atomically, and served duplicates raise PENDING Service Requests attached
 * to the activity (intervention deferred until approval).
 */
class ActivityInlineUploadTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA; // importing MDA

    private Mda $mdaB; // owner of the existing (duplicated) beneficiary

    private User $officer;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mdaA->id,
            'role_id' => Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id,
        ]);
        $this->programme = Programme::factory()->individual()->create(['eligibility' => null]);
    }

    private function token(): string
    {
        return $this->officer->createToken('t')->plainTextToken;
    }

    /** An existing beneficiary owned by MDA B that the upload's row 1 duplicates by NIN. */
    private function existingDuplicate(): Beneficiary
    {
        return Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaB->id,
            'nin' => '22200000011',
            'first_name' => 'Zainab',
            'last_name' => 'Umar',
            'date_of_birth' => '1980-01-01',
        ]);
    }

    private function csv(): string
    {
        return implode("\n", [
            'first_name,last_name,nin,bvn,phone,date_of_birth,gender,lga,ward',
            'Different,Person,22200000011,,08000000000,1999-09-09,male,dutse,Ward 3', // row1: exact NIN dup → serve
            'Amina,Yusuf,,,08031234567,1990-05-10,female,dutse,Ward 2',               // row2: unique NEW
            'Bad,Nin,123,,08099999999,2000-03-03,male,dutse,Ward 1',                  // row3: malformed NIN → rejected
        ]);
    }

    private function preview(): TestResponse
    {
        return $this->withToken($this->token())->post('/api/v1/activity-imports', [
            'programme_id' => $this->programme->id,
            'name' => 'Q1 Cash Round',
            'lga' => 'dutse',
            'budget_amount' => 5_000_000,
            'funding_source' => 'State budget',
            'file' => UploadedFile::fake()->createWithContent('people.csv', $this->csv()),
        ], ['Accept' => 'application/json']);
    }

    public function test_preview_runs_dedup_without_persisting_the_activity_or_beneficiaries(): void
    {
        $existing = $this->existingDuplicate();

        $batchId = $this->preview()->assertCreated()->json('data.id');
        $this->app['auth']->forgetGuards();

        // The batch is an UNBOUND preview (no activity yet) with the dedup cascade run.
        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batchId);
        $this->assertNull($batch->activity_id);
        $this->assertSame('preview_ready', $batch->status->value);
        $this->assertNotNull($batch->draft_activity);

        $preview = $this->withToken($this->token())->getJson("/api/v1/beneficiaries/imports/{$batchId}")->assertOk();
        $preview->assertJsonPath('data.rows.0.match.band', 'exact') // row1 duplicates the existing record
            ->assertJsonPath('data.rows.0.match.candidates.0.reveal.id', $existing->id);
        $this->assertFalse($preview->json('data.rows.2.is_valid')); // row3 malformed NIN → rejected

        // NOTHING is saved before confirm: no activity, no new beneficiaries.
        $this->assertSame(0, Activity::query()->withoutGlobalScope(MdaScope::class)->count());
        $this->assertSame(1, Beneficiary::query()->withoutGlobalScope(MdaScope::class)->count()); // only the pre-existing one
    }

    public function test_confirm_atomically_creates_activity_interventions_and_pending_service_requests(): void
    {
        $existing = $this->existingDuplicate();

        $batchId = $this->preview()->assertCreated()->json('data.id');
        $this->app['auth']->forgetGuards();

        // Officer chooses to SERVE the exact duplicate (row 1).
        $this->withToken($this->token())->postJson("/api/v1/beneficiaries/imports/{$batchId}/rows/1/resolve", [
            'resolution' => 'link',
            'beneficiary_id' => $existing->id,
        ])->assertOk();
        $this->app['auth']->forgetGuards();

        $response = $this->withToken($this->token())->postJson("/api/v1/activity-imports/{$batchId}/confirm")->assertCreated();

        // 1. The activity is created, MDA-owned, under its catalog programme.
        $activity = Activity::query()->withoutGlobalScope(MdaScope::class)->firstOrFail();
        $this->assertSame($this->mdaA->id, $activity->owner_mda_id);
        $this->assertSame($this->programme->id, $activity->programme_id);
        $response->assertJsonPath('data.activity.id', $activity->id);

        // 2. New (non-duplicate) beneficiary saved owned by MDA A, enrolled under the activity.
        $new = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->where('owner_mda_id', $this->mdaA->id)->get();
        $this->assertCount(1, $new); // row2 only (row3 malformed → rejected)
        $this->assertSame('Amina', $new->first()->first_name);

        $enrollments = Enrollment::query()->withoutGlobalScope(MdaScope::class)->get();
        $this->assertCount(1, $enrollments);
        $this->assertSame($activity->id, $enrollments->first()->activity_id);
        $this->assertSame($new->first()->id, $enrollments->first()->beneficiary_id);

        // 3. The served duplicate gets a PENDING Service Request ATTACHED TO THE ACTIVITY;
        //    ownership is unchanged and NO intervention is recorded (deferred).
        $sr = ServiceRequest::query()->firstOrFail();
        $this->assertSame('pending', $sr->status->value);
        $this->assertSame($this->mdaA->id, $sr->from_mda_id);
        $this->assertSame($this->mdaB->id, $sr->to_mda_id);
        $this->assertSame($activity->id, $sr->activity_id);
        $this->assertSame($existing->id, $sr->beneficiary_id);
        $this->assertSame($this->mdaB->id, $existing->fresh()->owner_mda_id); // ownership unchanged
        $this->assertFalse(Enrollment::query()->withoutGlobalScope(MdaScope::class)->where('beneficiary_id', $existing->id)->exists());

        // 4. All actions audited.
        $this->assertDatabaseHas('audit_log', ['action' => 'activity.created', 'entity_id' => $activity->id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'service_request.created', 'entity_id' => $sr->id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'beneficiary.created', 'entity_id' => $new->first()->id]);
    }

    public function test_confirm_is_refused_for_a_standalone_bound_batch(): void
    {
        // A normal Import-Center batch (bound to an activity) is not a wizard batch.
        $activity = Activity::factory()->forProgramme($this->programme, $this->mdaA)->create();
        $batch = ImportBatch::query()->create([
            'owner_mda_id' => $this->mdaA->id,
            'uploaded_by' => $this->officer->id,
            'original_filename' => 'x.csv',
            'stored_path' => 'imports/x.csv',
            'source' => 'csv',
            'activity_id' => $activity->id,
            'status' => 'preview_ready',
        ]);

        $this->withToken($this->token())->postJson("/api/v1/activity-imports/{$batch->id}/confirm")
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'NOT_A_WIZARD_BATCH');
    }
}
