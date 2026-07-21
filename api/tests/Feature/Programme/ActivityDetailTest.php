<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Full "View Activity" detail endpoint (PRD §10): the catalog programme, target vs
 * ACTUAL beneficiary counts, the beneficiaries/interventions under the activity, its
 * import/validation summary, and the attached request-to-serve items — owner-MDA
 * scoped, permission-gated, PII masked.
 */
class ActivityDetailTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;   // owns the activity

    private Mda $mdaB;   // owns a served-duplicate beneficiary

    /** @var array<string, User> */
    private array $users = [];

    private Activity $activity;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['oversight'] = $this->user($this->mdaA, RoleKey::SpCoordination);

        $this->programme = Programme::factory()->individual()->create(['name' => 'Cash Transfer']);
        $this->activity = Activity::factory()->forProgramme($this->programme, $this->mdaA)->create([
            'name' => 'Q1 Cash Round',
            'involves_beneficiaries' => true,
            'target_beneficiaries' => 5,
        ]);

        // Two new beneficiaries owned by MDA A, enrolled (interventions) under the activity.
        $ada = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '12345678901', 'bvn' => '22345678902']);
        $bala = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Bala', 'last_name' => 'Sule']);
        foreach ([$ada, $bala] as $beneficiary) {
            Enrollment::factory()->create([
                'programme_id' => $this->programme->id,
                'activity_id' => $this->activity->id,
                'mda_id' => $this->mdaA->id,
                'beneficiary_id' => $beneficiary->id,
                'enrolled_by' => $this->users['officerA']->id,
            ]);
        }

        // A served duplicate owned by MDA B → a PENDING Service Request under the activity.
        $foreign = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Zara', 'last_name' => 'Musa', 'nin' => '99999999999']);
        ServiceRequest::create([
            'beneficiary_id' => $foreign->id,
            'from_mda_id' => $this->mdaA->id,
            'to_mda_id' => $this->mdaB->id,
            'activity_id' => $this->activity->id,
            'status' => 'pending',
            'requested_by' => $this->users['officerA']->id,
        ]);

        // The activity's import batch + its validation summary.
        ImportBatch::create([
            'owner_mda_id' => $this->mdaA->id,
            'uploaded_by' => $this->users['officerA']->id,
            'original_filename' => 'people.csv',
            'stored_path' => 'imports/people.csv',
            'source' => 'csv',
            'activity_id' => $this->activity->id,
            'status' => 'completed',
            'total_rows' => 3,
            'valid_rows' => 2,
            'invalid_rows' => 1,
            'rejected_rows' => 1,
            'committed_rows' => 1,
            'served_rows' => 1,
            'skipped_rows' => 0,
        ]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function fetch(string $key): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->getJson("/api/v1/activities/{$this->activity->id}");
        $this->app['auth']->forgetGuards();

        return $response;
    }

    public function test_returns_the_complete_activity_picture(): void
    {
        $this->fetch('officerA')
            ->assertOk()
            ->assertJsonPath('data.name', 'Q1 Cash Round')
            ->assertJsonPath('data.involves_beneficiaries', true)
            ->assertJsonPath('data.programme.name', 'Cash Transfer')
            // Target vs ACTUAL (2 enrolled).
            ->assertJsonPath('data.counts.target', 5)
            ->assertJsonPath('data.counts.actual', 2)
            ->assertJsonPath('data.counts.pending_service_requests', 1)
            // Beneficiaries/interventions under the activity.
            ->assertJsonCount(2, 'data.beneficiaries')
            // Import/validation summary of its batch.
            ->assertJsonPath('data.import_summary.valid_rows', 2)
            ->assertJsonPath('data.import_summary.rejected_rows', 1)
            ->assertJsonPath('data.import_summary.served_rows', 1)
            // Attached request-to-serve items with status.
            ->assertJsonCount(1, 'data.service_requests')
            ->assertJsonPath('data.service_requests.0.status', 'pending')
            ->assertJsonPath('data.service_requests.0.beneficiary_name', 'Zara Musa');
    }

    public function test_masks_pii_in_the_beneficiary_list_and_never_reveals_foreign_identifiers(): void
    {
        $response = $this->fetch('officerA')->assertOk();

        // NIN/BVN of enrolled beneficiaries are masked to last-4.
        $ada = collect($response->json('data.beneficiaries'))->firstWhere('full_name', 'Ada Okoye');
        $this->assertSame('•••••••8901', $ada['nin']);
        $this->assertSame('•••••••8902', $ada['bvn']);

        // No raw identifier appears anywhere in the payload — including the served
        // (foreign-owned) beneficiary, which is name-only.
        $body = $response->getContent();
        $this->assertStringNotContainsString('12345678901', $body);
        $this->assertStringNotContainsString('22345678902', $body);
        $this->assertStringNotContainsString('99999999999', $body);
    }

    public function test_is_owner_scoped(): void
    {
        // Another MDA cannot see the activity at all (out of scope → 404).
        $this->fetch('officerB')->assertStatus(404);

        // Oversight (cross-MDA) sees the full picture.
        $this->fetch('oversight')->assertOk()->assertJsonPath('data.counts.actual', 2);
    }

    public function test_requires_the_view_permission(): void
    {
        $noRole = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);

        $this->withToken($noRole->createToken('t')->plainTextToken)
            ->getJson("/api/v1/activities/{$this->activity->id}")
            ->assertStatus(403);
    }
}
