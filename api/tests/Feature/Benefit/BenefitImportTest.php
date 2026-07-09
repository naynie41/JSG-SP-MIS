<?php

declare(strict_types=1);

namespace Tests\Feature\Benefit;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Jobs\CommitBenefitImport;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Bulk benefit delivery via the import pipeline (PRD FR-BEN-01/02, §8.3): preview
 * with per-row validation, partial commit of valid rows only, idempotency, and
 * eligibility flagging. Creates benefits, not beneficiaries.
 */
class BenefitImportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $programmeA;

    private Activity $activityA;

    private Beneficiary $benef1;

    private Beneficiary $benef2;

    private Beneficiary $benef3; // not enrolled

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
        $this->users['viewer'] = $this->user($this->mdaA, RoleKey::MneOfficer); // benefit.view only
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive);

        $this->programmeA = Programme::factory()->individual()->create(['eligibility' => null]);
        $this->activityA = Activity::factory()->forProgramme($this->programmeA, $this->mdaA)->create();

        $this->benef1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->benef2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'nin' => '55500011122']);
        $this->benef3 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $this->enroll($this->benef1);
        $this->enroll($this->benef2);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function enroll(Beneficiary $beneficiary, ?Programme $programme = null): void
    {
        $programme ??= $this->programmeA;
        Enrollment::factory()->create([
            'programme_id' => $programme->id,
            'mda_id' => $this->mdaA->id, // the enrolling/delivering MDA
            'beneficiary_id' => $beneficiary->id,
            'status' => EnrollmentStatus::Enrolled,
        ]);
    }

    private function upload(string $key, string $activityId, string $csv): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->post(
            '/api/v1/benefit-imports',
            ['file' => UploadedFile::fake()->createWithContent('deliveries.csv', $csv), 'activity_id' => $activityId],
            ['Accept' => 'application/json'],
        );
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function read(string $key, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->getJson($url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function confirm(string $key, string $batchId): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->postJson("/api/v1/benefit-imports/{$batchId}/confirm");
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private const HEADER = "beneficiary_id,nin,bvn,benefit_type,quantity,unit,monetary_value,funding_source,delivery_date,verification_method,verification_reference,notes\n";

    public function test_preview_reports_per_row_validation(): void
    {
        $csv = self::HEADER
            ."{$this->benef1->id},,,cash,1,transfer,500000,State budget,2026-07-03,field_confirmation,,Q1\n"   // valid (by id)
            .",55500011122,,food,2,bags,,State budget,2026-07-03,,,\n"                                          // valid (by nin)
            ."{$this->benef3->id},,,cash,1,,300000,,2026-07-03,,,\n"                                            // error: not enrolled
            .",99999999999,,cash,1,,,,2026-07-03,,,\n";                                                          // error: unmatched

        $batchId = $this->upload('officerA', $this->activityA->id, $csv)->assertCreated()->json('data.id');

        $this->read('officerA', "/api/v1/benefit-imports/{$batchId}")
            ->assertOk()
            ->assertJsonPath('data.status', 'preview_ready')
            ->assertJsonPath('data.summary.total_rows', 4)
            ->assertJsonPath('data.summary.valid_rows', 2)
            ->assertJsonPath('data.summary.invalid_rows', 2);
    }

    public function test_confirm_commits_valid_rows_only_with_delivering_mda_and_audit(): void
    {
        $csv = self::HEADER
            ."{$this->benef1->id},,,cash,1,transfer,500000,State budget,2026-07-03,field_confirmation,,Q1\n"
            .",55500011122,,food,2,bags,,,2026-07-03,,,\n"
            ."{$this->benef3->id},,,cash,1,,300000,,2026-07-03,,,\n"; // not enrolled → invalid

        $batchId = $this->upload('officerA', $this->activityA->id, $csv)->assertCreated()->json('data.id');
        $this->confirm('officerA', $batchId)->assertOk();

        $batch = BenefitImportBatch::query()->withoutGlobalScopes()->findOrFail($batchId);
        $this->assertSame(ImportStatus::Completed, $batch->status);
        $this->assertSame(2, $batch->committed_rows);

        // Exactly the two valid rows became ledger entries, delivered by the importer.
        $this->assertSame(2, Benefit::query()->withoutGlobalScopes()->count());
        $cash = Benefit::query()->withoutGlobalScopes()->where('beneficiary_id', $this->benef1->id)->firstOrFail();
        $this->assertSame($this->mdaA->id, $cash->mda_id);
        $this->assertSame('verified', $cash->status->value);
        $this->assertSame(500000, $cash->monetary_value);
        $this->assertDatabaseHas('audit_log', ['action' => 'benefit.created', 'entity_id' => $cash->id]);

        // The invalid row is reported, not dropped, and created no benefit.
        $this->assertSame(0, Benefit::query()->withoutGlobalScopes()->where('beneficiary_id', $this->benef3->id)->count());

        // Every ledger entry rolls up to the bound activity + its programme
        // (activity-first, PRD §9/ARCH §12.3) — the intervention is attributed to
        // the activity the delivery list was keyed to, not left free-floating.
        foreach (Benefit::query()->withoutGlobalScopes()->get() as $benefit) {
            $this->assertSame($this->activityA->id, $benefit->activity_id);
            $this->assertSame($this->programmeA->id, $benefit->programme_id);
        }
    }

    public function test_upload_without_a_valid_activity_is_refused(): void
    {
        $token = $this->users['officerA']->createToken('t')->plainTextToken;
        $csv = self::HEADER."{$this->benef1->id},,,cash,1,,100000,,2026-07-03,,,\n";

        // No activity at all — an import cannot bind to a missing activity.
        $this->withToken($token)
            ->post('/api/v1/benefit-imports', ['file' => UploadedFile::fake()->createWithContent('d.csv', $csv)], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'activity_id']);
        $this->app['auth']->forgetGuards();

        // A non-existent activity is refused too — the reference must resolve to a
        // real Activity, never a bare/unknown id.
        $this->withToken($token)
            ->post('/api/v1/benefit-imports', [
                'file' => UploadedFile::fake()->createWithContent('d.csv', $csv),
                'activity_id' => '00000000-0000-0000-0000-000000000000',
            ], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'activity_id']);
        $this->app['auth']->forgetGuards();

        $this->assertSame(0, BenefitImportBatch::query()->withoutGlobalScopes()->count());
    }

    public function test_rerunning_the_commit_does_not_double_record(): void
    {
        $csv = self::HEADER.",55500011122,,food,2,bags,,,2026-07-03,,,\n";
        $batchId = $this->upload('officerA', $this->activityA->id, $csv)->assertCreated()->json('data.id');
        $this->confirm('officerA', $batchId)->assertOk();
        $this->assertSame(1, Benefit::query()->withoutGlobalScopes()->count());

        // Force a re-run of the commit over the same batch: committed rows are stamped
        // with benefit_id and skipped, so nothing is double-recorded.
        $batch = BenefitImportBatch::query()->withoutGlobalScopes()->findOrFail($batchId);
        $batch->update(['status' => ImportStatus::PreviewReady]);
        CommitBenefitImport::dispatchSync($batchId, $this->users['officerA']->id);
        $this->app['auth']->forgetGuards();

        $this->assertSame(1, Benefit::query()->withoutGlobalScopes()->count());
    }

    public function test_eligibility_is_flagged_advisory_or_blocks_when_enforced(): void
    {
        $programme = Programme::factory()->individual()->create([
            'eligibility' => [['attribute' => 'lga', 'value' => 'dutse']],
            'enforce_eligibility' => false,
        ]);
        $activity = Activity::factory()->forProgramme($programme, $this->mdaA)->create();
        $ineligible = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'gumel']);
        $this->enroll($ineligible, $programme);

        $csv = self::HEADER."{$ineligible->id},,,cash,1,,100000,,2026-07-03,,,\n";
        $batchId = $this->upload('officerA', $activity->id, $csv)->assertCreated()->json('data.id');

        // Advisory: the row is valid but flagged, and commits.
        $this->read('officerA', "/api/v1/benefit-imports/{$batchId}")
            ->assertOk()
            ->assertJsonPath('data.summary.valid_rows', 1)
            ->assertJsonPath('data.rows.0.eligibility_flagged', true);
        $this->confirm('officerA', $batchId)->assertOk();
        $this->assertSame(1, Benefit::query()->withoutGlobalScopes()->count());

        // Enforced: the ineligible row becomes invalid and does not commit.
        $programme->update(['enforce_eligibility' => true]);
        $other = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'gumel']);
        $this->enroll($other, $programme);
        $csv2 = self::HEADER."{$other->id},,,cash,1,,100000,,2026-07-03,,,\n";
        $batchId2 = $this->upload('officerA', $activity->id, $csv2)->assertCreated()->json('data.id');
        $this->read('officerA', "/api/v1/benefit-imports/{$batchId2}")->assertOk()->assertJsonPath('data.summary.invalid_rows', 1);
    }

    public function test_scoping_and_permissions(): void
    {
        $csv = self::HEADER."{$this->benef1->id},,,cash,1,,500000,,2026-07-03,,,\n";

        // Another MDA cannot upload to MDA A's activity.
        $this->upload('officerB', $this->activityA->id, $csv)->assertStatus(403);

        // A view-only role cannot upload (no benefit.create).
        $this->upload('viewer', $this->activityA->id, $csv)->assertStatus(403);

        // A valid batch is scoped to the importing MDA; oversight sees all.
        $this->upload('officerA', $this->activityA->id, $csv)->assertCreated();
        $this->read('officerA', '/api/v1/benefit-imports')->assertOk()->assertJsonCount(1, 'data');
        $this->read('officerB', '/api/v1/benefit-imports')->assertOk()->assertJsonCount(0, 'data');
        $this->read('oversight', '/api/v1/benefit-imports')->assertOk()->assertJsonCount(1, 'data');
    }
}
