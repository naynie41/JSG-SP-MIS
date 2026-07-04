<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ServeRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\ServeRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Beneficiary/household enrollment (PRD FR-PRG-03): individual + household,
 * single + bulk, type-match enforcement, the serve seam (non-owner enrollment
 * without ownership transfer), advisory/enforced eligibility, scoping, RBAC, audit.
 */
class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $individualA;

    private Programme $householdA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['viewer'] = $this->user($this->mdaA, RoleKey::MneOfficer); // enrollment.view only
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive);

        $this->individualA = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id, 'eligibility' => null]);
        $this->householdA = Programme::factory()->household()->create(['owner_mda_id' => $this->mdaA->id, 'eligibility' => null]);
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    public function test_owner_can_enroll_an_individual_beneficiary(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['beneficiary_id' => $beneficiary->id])
            ->assertCreated()
            ->assertJsonPath('data.beneficiary_id', $beneficiary->id)
            ->assertJsonPath('data.mda_id', $this->mdaA->id)
            ->assertJsonPath('data.status', 'enrolled')
            ->assertJsonPath('data.eligibility_flagged', false);

        $enrollment = Enrollment::query()->firstOrFail();
        $this->assertDatabaseHas('audit_log', [
            'action' => 'enrollment.created',
            'entity_id' => $enrollment->id,
            'actor_id' => $this->users['officerA']->id,
        ]);
    }

    public function test_owner_can_enroll_a_household_into_a_household_programme(): void
    {
        $household = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->householdA->id}/enrollments", ['household_id' => $household->id])
            ->assertCreated()
            ->assertJsonPath('data.household_id', $household->id)
            ->assertJsonPath('data.beneficiary_id', null);
    }

    public function test_type_mismatch_is_rejected(): void
    {
        $household = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);

        // Household into an individual programme.
        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['household_id' => $household->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'TYPE_MISMATCH');

        // Beneficiary into a household programme.
        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->householdA->id}/enrollments", ['beneficiary_id' => $beneficiary->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'TYPE_MISMATCH');
    }

    public function test_duplicate_enrollment_is_a_conflict(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        Enrollment::factory()->create([
            'programme_id' => $this->individualA->id,
            'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $beneficiary->id,
        ]);

        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['beneficiary_id' => $beneficiary->id])
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'ENROLLMENT_EXISTS');
    }

    public function test_bulk_enrollment_reports_per_target_outcomes(): void
    {
        $beneficiaries = Beneficiary::factory()->count(3)->create(['owner_mda_id' => $this->mdaA->id]);
        // Pre-enroll the first so it is skipped.
        Enrollment::factory()->create([
            'programme_id' => $this->individualA->id,
            'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $beneficiaries[0]->id,
        ]);

        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments/bulk", [
            'beneficiary_ids' => $beneficiaries->pluck('id')->all(),
        ])
            ->assertOk()
            ->assertJsonPath('data.requested', 3)
            ->assertJsonPath('data.enrolled', 2)
            ->assertJsonPath('data.skipped', 1)
            ->assertJsonPath('data.rejected', 0);

        $this->assertSame(3, Enrollment::query()->where('programme_id', $this->individualA->id)->count());
    }

    public function test_serving_mda_enrolls_a_non_owned_beneficiary_without_taking_ownership(): void
    {
        // Beneficiary owned by MDA B; MDA A runs the programme.
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);

        // Without a serve relationship → rejected.
        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['beneficiary_id' => $beneficiary->id])
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'SERVE_ACCESS_REQUIRED');

        // Grant serve access via an accepted request-to-serve (Phase 3 seam).
        ServeRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $this->mdaA->id,
            'to_mda_id' => $this->mdaB->id,
            'status' => ServeRequestStatus::Accepted,
        ]);

        $this->send('officerA', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['beneficiary_id' => $beneficiary->id])
            ->assertCreated()
            ->assertJsonPath('data.mda_id', $this->mdaA->id); // enrolling MDA

        // Ownership is unchanged.
        $this->assertSame($this->mdaB->id, $beneficiary->fresh()->owner_mda_id);
    }

    public function test_eligibility_is_advisory_by_default_and_enforced_when_configured(): void
    {
        $programme = Programme::factory()->individual()->create([
            'owner_mda_id' => $this->mdaA->id,
            'eligibility' => [['attribute' => 'lga', 'value' => 'dutse']],
            'enforce_eligibility' => false,
        ]);
        $ineligible = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'gumel']);

        // Advisory: enrolled but flagged.
        $this->send('officerA', 'POST', "/api/v1/programmes/{$programme->id}/enrollments", ['beneficiary_id' => $ineligible->id])
            ->assertCreated()
            ->assertJsonPath('data.eligibility_flagged', true)
            ->assertJsonPath('data.eligibility_notes', ['lga']);

        // Enforced: rejected.
        $programme->update(['enforce_eligibility' => true]);
        $other = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'gumel']);
        $this->send('officerA', 'POST', "/api/v1/programmes/{$programme->id}/enrollments", ['beneficiary_id' => $other->id])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'INELIGIBLE');
    }

    public function test_permissions_and_scoping(): void
    {
        // Build all fixtures up front (creating Auditable models between authenticated
        // requests would cache a stale user and break later auth in the same test).
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $ownB = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id]);
        Enrollment::factory()->create(['programme_id' => $this->individualA->id, 'mda_id' => $this->mdaA->id, 'beneficiary_id' => $beneficiary->id]);

        // enrollment.view only → cannot enroll.
        $this->send('viewer', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['beneficiary_id' => $ownB->id])
            ->assertStatus(403);

        // Another MDA cannot enroll into a programme it does not own.
        $this->send('officerB', 'POST', "/api/v1/programmes/{$this->individualA->id}/enrollments", ['beneficiary_id' => $ownB->id])
            ->assertStatus(403);

        // List is scoped to the enrolling MDA; oversight sees all.
        $this->send('officerA', 'GET', '/api/v1/enrollments')->assertOk()->assertJsonCount(1, 'data');
        $this->send('officerB', 'GET', '/api/v1/enrollments')->assertOk()->assertJsonCount(0, 'data');
        $this->send('oversight', 'GET', '/api/v1/enrollments')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_owner_can_exit_an_enrollment_but_another_mda_cannot(): void
    {
        $beneficiary = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        $enrollment = Enrollment::factory()->create(['programme_id' => $this->individualA->id, 'mda_id' => $this->mdaA->id, 'beneficiary_id' => $beneficiary->id]);

        $this->send('officerB', 'PATCH', "/api/v1/enrollments/{$enrollment->id}", ['status' => 'exited'])->assertStatus(403);

        $this->send('officerA', 'PATCH', "/api/v1/enrollments/{$enrollment->id}", ['status' => 'exited', 'exit_reason' => 'Graduated out'])
            ->assertOk()
            ->assertJsonPath('data.status', 'exited')
            ->assertJsonPath('data.exit_reason', 'Graduated out');

        $this->assertNotNull($enrollment->fresh()->exited_on);
    }
}
